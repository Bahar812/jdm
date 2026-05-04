<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = CarbonImmutable::now();
        $chartStart = $now->subWeeks(5)->startOfWeek();
        $chartEnd = $now->endOfWeek();
        $paidOrdersForCharts = $this->paidOrdersBetween($chartStart, $chartEnd);
        $orderSummary = Order::query()
            ->selectRaw('COUNT(*) as total_order_count')
            ->selectRaw('COALESCE(SUM(CASE WHEN payment_status = ? THEN total_amount ELSE 0 END), 0) as paid_revenue', [Order::PAYMENT_PAID])
            ->selectRaw('COALESCE(SUM(CASE WHEN payment_status = ? THEN 1 ELSE 0 END), 0) as paid_order_count', [Order::PAYMENT_PAID])
            ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) as pending_order_count', [Order::STATUS_PENDING])
            ->first();

        $recentOrders = Order::query()
            ->select(['id', 'order_number', 'customer_name', 'total_amount', 'status', 'payment_status', 'created_at'])
            ->latest()
            ->withCount('items')
            ->take(8)
            ->get();

        $totalRevenue = (int) ($orderSummary->paid_revenue ?? 0);
        $totalOrderCount = (int) ($orderSummary->total_order_count ?? 0);
        $paidOrderCount = (int) ($orderSummary->paid_order_count ?? 0);
        $todaySales = $this->salesSummaryFromOrders($paidOrdersForCharts, $now->startOfDay(), $now->endOfDay());
        $weekSales = $this->salesSummaryFromOrders($paidOrdersForCharts, $now->startOfWeek(), $now->endOfWeek());

        $salesByStatus = Order::query()
            ->selectRaw('payment_status, COUNT(*) as total')
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        return view('admin.dashboard', [
            'memberCount' => User::query()->where('role', 'customer')->count(),
            'productCount' => Product::query()->count(),
            'pendingOrders' => (int) ($orderSummary->pending_order_count ?? 0),
            'totalRevenue' => $totalRevenue,
            'paidRevenue' => $totalRevenue,
            'totalOrderCount' => $totalOrderCount,
            'paidOrderCount' => $paidOrderCount,
            'averageOrderValue' => $paidOrderCount > 0 ? (int) round($totalRevenue / $paidOrderCount) : 0,
            'todayRevenue' => $todaySales['revenue'],
            'todayOrderCount' => $todaySales['orders'],
            'weekRevenue' => $weekSales['revenue'],
            'weekOrderCount' => $weekSales['orders'],
            'dailySalesChart' => $this->dailySalesChart($now, $paidOrdersForCharts),
            'weeklySalesChart' => $this->weeklySalesChart($now, $paidOrdersForCharts),
            'topProducts' => OrderItem::query()
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->selectRaw('order_items.product_name, SUM(order_items.quantity) as total_quantity, SUM(order_items.subtotal) as total_revenue')
                ->where('orders.payment_status', Order::PAYMENT_PAID)
                ->groupBy('order_items.product_name')
                ->orderByDesc('total_quantity')
                ->take(5)
                ->get(),
            'orderActivities' => OrderStatusHistory::query()
                ->select(['id', 'order_id', 'from_status', 'to_status', 'from_payment_status', 'to_payment_status', 'source', 'changed_by', 'created_at'])
                ->with('order:id,order_number,customer_name,total_amount,status,payment_status')
                ->latest()
                ->take(6)
                ->get(),
            'lowStockProducts' => Product::query()
                ->select(['id', 'name', 'slug', 'stock', 'unit'])
                ->where('stock', '<=', 15)
                ->orderBy('stock')
                ->take(6)
                ->get(),
            'recentOrders' => $recentOrders,
            'salesByStatus' => $salesByStatus,
        ]);
    }

    private function salesSummaryFromOrders(Collection $orders, CarbonImmutable $start, CarbonImmutable $end): array
    {
        $filteredOrders = $orders->filter(function (Order $order) use ($start, $end): bool {
            $timestamp = $this->salesTimestamp($order);

            return $timestamp->greaterThanOrEqualTo($start) && $timestamp->lessThanOrEqualTo($end);
        });

        return [
            'orders' => $filteredOrders->count(),
            'revenue' => (int) $filteredOrders->sum('total_amount'),
        ];
    }

    private function dailySalesChart(CarbonImmutable $now, Collection $orders): array
    {
        $start = $now->subDays(6)->startOfDay();
        $buckets = [];

        for ($day = 0; $day < 7; $day++) {
            $date = $start->addDays($day);
            $buckets[$date->toDateString()] = [
                'label' => $date->format('d M'),
                'revenue' => 0,
                'orders' => 0,
            ];
        }

        foreach ($orders as $order) {
            $key = $this->salesTimestamp($order)->toDateString();

            if (! array_key_exists($key, $buckets)) {
                continue;
            }

            $buckets[$key]['orders']++;
            $buckets[$key]['revenue'] += (int) $order->total_amount;
        }

        return $this->formatChart(array_values($buckets));
    }

    private function weeklySalesChart(CarbonImmutable $now, Collection $orders): array
    {
        $start = $now->subWeeks(5)->startOfWeek();
        $buckets = [];

        for ($week = 0; $week < 6; $week++) {
            $weekStart = $start->addWeeks($week);
            $buckets[$weekStart->toDateString()] = [
                'label' => $weekStart->format('d M').' - '.$weekStart->endOfWeek()->format('d M'),
                'revenue' => 0,
                'orders' => 0,
            ];
        }

        foreach ($orders as $order) {
            $key = $this->salesTimestamp($order)->startOfWeek()->toDateString();

            if (! array_key_exists($key, $buckets)) {
                continue;
            }

            $buckets[$key]['orders']++;
            $buckets[$key]['revenue'] += (int) $order->total_amount;
        }

        return $this->formatChart(array_values($buckets));
    }

    private function paidOrdersBetween(CarbonImmutable $start, CarbonImmutable $end)
    {
        return Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->where(function ($query) use ($start, $end): void {
                $query->whereBetween('paid_at', [$start, $end])
                    ->orWhere(function ($fallback) use ($start, $end): void {
                        $fallback->whereNull('paid_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->get(['id', 'total_amount', 'paid_at', 'created_at']);
    }

    private function salesTimestamp(Order $order): CarbonImmutable
    {
        $timestamp = $order->paid_at ?: $order->created_at;

        if ($timestamp instanceof \DateTimeInterface) {
            return CarbonImmutable::instance($timestamp);
        }

        return CarbonImmutable::parse($timestamp);
    }

    private function formatChart(array $entries): array
    {
        $maxRevenue = max(1, ...array_column($entries, 'revenue'));
        $totalRevenue = array_sum(array_column($entries, 'revenue'));
        $totalOrders = array_sum(array_column($entries, 'orders'));

        foreach ($entries as $index => $entry) {
            $entries[$index]['height'] = $entry['revenue'] > 0
                ? max(8, (int) round(($entry['revenue'] / $maxRevenue) * 100))
                : 2;
        }

        return [
            'entries' => $entries,
            'maxRevenue' => $maxRevenue,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
        ];
    }
}
