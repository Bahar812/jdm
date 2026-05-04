<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusUpdateRequest;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\OrderStatusService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderStatusService $orderStatusService,
        private readonly InventoryService $inventoryService,
    ) {}

    public function index(Request $request): View
    {
        $orderStatusOptions = Order::orderStatuses();
        $paymentStatusOptions = Order::paymentStatuses();

        $status = $request->query('status');
        $payment = $request->query('payment');
        $search = trim((string) $request->query('q'));

        if (! in_array($status, $orderStatusOptions, true)) {
            $status = null;
        }

        if (! in_array($payment, $paymentStatusOptions, true)) {
            $payment = null;
        }

        $orders = Order::query()
            ->select(['id', 'order_number', 'customer_name', 'customer_phone', 'total_amount', 'status', 'payment_status', 'created_at'])
            ->withCount('items')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($payment, fn ($query) => $query->where('payment_status', $payment))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status,
            'payment' => $payment,
            'search' => $search,
            'orderStatusOptions' => $orderStatusOptions,
            'paymentStatusOptions' => $paymentStatusOptions,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load([
            'items.product',
            'statusHistories' => fn ($query) => $query->with('changedBy')->latest(),
        ]);

        return view('admin.orders.show', [
            'order' => $order,
            'orderStatusOptions' => Order::orderStatuses(),
            'paymentStatusOptions' => Order::paymentStatuses(),
        ]);
    }

    public function invoice(Order $order): Response
    {
        abort_unless($order->isPaid(), 404);

        $pdf = Pdf::loadView('admin.orders.invoice', [
            'order' => $order->load('items.product'),
        ])->setPaper('a4');

        return $pdf->download('invoice-'.$order->order_number.'.pdf');
    }

    public function update(OrderStatusUpdateRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();
        $targetPaymentStatus = $request->paymentStatus();
        $targetStatus = $request->targetStatus();

        DB::transaction(function () use ($order, $validated, $targetPaymentStatus, $targetStatus): void {
            $wasPaid = $order->isPaid();

            $this->orderStatusService->applyStateChange($order, [
                'status' => $targetStatus,
                'payment_status' => $targetPaymentStatus,
                'paid_at' => $targetPaymentStatus === Order::PAYMENT_PAID ? ($order->paid_at ?: now()) : $order->paid_at,
                'notes' => $validated['notes'] ?? $order->notes,
            ], source: 'admin_dashboard', note: $validated['notes'] ?? 'Update manual status order dari admin.', changedBy: auth()->id());

            if (
                ! $wasPaid &&
                $targetPaymentStatus === Order::PAYMENT_PAID &&
                ! $order->inventoryMovements()->where('type', 'out')->exists()
            ) {
                $freshOrder = $order->fresh('items');
                if ($freshOrder) {
                    $this->inventoryService->applyStockOutForPaidOrder(
                        order: $freshOrder,
                        createdBy: auth()->id(),
                        note: 'Stok keluar untuk order '.$order->order_number.' (manual update admin)',
                    );
                }
            }
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status order berhasil diperbarui.');
    }
}
