<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\MidtransService;
use App\Services\OrderStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly MidtransService $midtransService,
        private readonly OrderStatusService $orderStatusService,
    ) {}

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $items = $this->cartService->items();
        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($validated, $items) {
            $orderItems = $this->prepareOrderItems($items);
            $totalAmount = array_sum(array_column($orderItems, 'subtotal'));

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => 'midtrans',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            $this->orderStatusService->recordInitialState(
                $order,
                source: 'checkout',
                note: 'Order dibuat dari checkout.',
                changedBy: Auth::id(),
            );

            return $order->load('items');
        });

        if ($this->midtransService->isConfigured() && $order->total_amount > 0) {
            try {
                $snapToken = $this->midtransService->createSnapToken($order);
                $order->update([
                    'midtrans_snap_token' => $snapToken,
                    'midtrans_redirect_url' => route('checkout.show', $order),
                ]);
            } catch (\Throwable $exception) {
                report($exception);

                return redirect()->route('cart')
                    ->with('error', 'Gagal membuat transaksi Midtrans. Cek konfigurasi API key.');
            }
        }

        $this->cartService->clear();

        $redirect = redirect()->route('checkout.show', $order)
            ->with('success', 'Order berhasil dibuat. Popup pembayaran Midtrans akan muncul otomatis.');

        if ($order->midtrans_snap_token && config('services.midtrans.client_key')) {
            $redirect->with('open_midtrans_popup', true);
        }

        return $redirect;
    }

    public function show(Order $order): View
    {
        return view('checkout.show', [
            'order' => $order->load('items'),
            'clientKey' => config('services.midtrans.client_key'),
            'snapJsUrl' => (bool) config('services.midtrans.is_production', false)
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js',
        ]);
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }

    private function prepareOrderItems($items): array
    {
        $rawCart = $this->cartService->getRawCart();
        $productIds = $items
            ->map(fn (array $item): int => (int) $item['product']->id)
            ->unique()
            ->values()
            ->all();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        return $items->map(function (array $item) use ($products, $rawCart): array {
            $product = $products->get((int) $item['product']->id);
            $quantity = max(1, (int) ($rawCart[$item['product']->id] ?? $item['quantity']));

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'cart' => 'Salah satu produk di keranjang sudah tidak aktif.',
                ]);
            }

            if ($quantity > (int) $product->stock) {
                throw ValidationException::withMessages([
                    'cart' => 'Stok '.$product->name.' tidak mencukupi untuk quantity checkout.',
                ]);
            }

            $price = (int) $product->price;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'category' => $product->category,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $price * $quantity,
            ];
        })->all();
    }
}
