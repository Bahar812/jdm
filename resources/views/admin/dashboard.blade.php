@extends('layouts.admin')

@section('admin_kicker', 'Overview')
@section('admin_title', 'Analytics Dashboard')

@section('admin_content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Revenue</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($paidOrderCount) }} order lunas</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Jumlah Order</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalOrderCount) }}</p>
            <p class="mt-2 text-xs text-slate-500">Rata-rata Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Penjualan Hari Ini</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($todayOrderCount) }} order lunas</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Penjualan Minggu Ini</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($weekRevenue, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ number_format($weekOrderCount) }} order lunas</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Grafik Penjualan Harian</h2>
                    <p class="mt-1 text-sm text-slate-500">Revenue order lunas dalam 7 hari terakhir.</p>
                </div>
                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-right">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-emerald-700">Total</p>
                    <p class="text-sm font-bold text-emerald-700">Rp {{ number_format($dailySalesChart['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <div class="flex h-64 min-w-[640px] items-end gap-3 border-b border-slate-200 pb-6">
                    @foreach ($dailySalesChart['entries'] as $entry)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                            <p class="h-8 text-center text-[10px] font-semibold leading-tight text-slate-500">
                                Rp {{ number_format($entry['revenue'], 0, ',', '.') }}
                            </p>
                            <div class="flex h-40 w-full items-end justify-center">
                                <div
                                    class="w-10 rounded-t-xl bg-slate-900 transition hover:bg-emerald-600"
                                    style="height: {{ $entry['height'] }}%;"
                                    title="{{ $entry['orders'] }} order - Rp {{ number_format($entry['revenue'], 0, ',', '.') }}"
                                ></div>
                            </div>
                            <p class="text-xs font-semibold text-slate-700">{{ $entry['label'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $entry['orders'] }} order</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Aktivitas Order</h2>
                    <p class="mt-1 text-sm text-slate-500">Notifikasi order terbaru.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Live</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse ($orderActivities as $activity)
                    @php
                        $order = $activity->order;

                        if (! $order) {
                            continue;
                        }

                        if ($activity->from_status === null && $activity->from_payment_status === null) {
                            $activityTitle = 'Order baru diterima';
                            $activityText = $order->customer_name.' membuat order '.$order->order_number;
                        } elseif ($activity->from_payment_status !== $activity->to_payment_status) {
                            $activityTitle = 'Update pembayaran';
                            $activityText = $order->order_number.' menjadi '.\App\Models\Order::paymentStatusLabel((string) $activity->to_payment_status);
                        } else {
                            $activityTitle = 'Update status order';
                            $activityText = $order->order_number.' menjadi '.\App\Models\Order::statusLabel((string) $activity->to_status);
                        }
                    @endphp
                    <a href="{{ route('admin.orders.show', $order) }}" class="block rounded-xl border border-slate-100 px-3 py-3 transition hover:border-slate-300 hover:bg-slate-50">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900">{{ $activityTitle }}</p>
                                <p class="mt-1 text-xs leading-5 text-slate-600">{{ $activityText }}</p>
                                <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">{{ $activity->created_at?->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="rounded-xl bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">Belum ada aktivitas order.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Grafik Penjualan Mingguan</h2>
                    <p class="mt-1 text-sm text-slate-500">Akumulasi revenue order lunas per minggu.</p>
                </div>
                <div class="rounded-xl bg-slate-50 px-3 py-2 text-right">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">6 Minggu</p>
                    <p class="text-sm font-bold text-slate-900">Rp {{ number_format($weeklySalesChart['totalRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($weeklySalesChart['entries'] as $entry)
                    <div class="grid gap-2 sm:grid-cols-[120px_1fr_120px] sm:items-center">
                        <div>
                            <p class="text-xs font-semibold text-slate-700">{{ $entry['label'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $entry['orders'] }} order</p>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: {{ $entry['height'] }}%;"></div>
                        </div>
                        <p class="text-sm font-bold text-slate-900 sm:text-right">Rp {{ number_format($entry['revenue'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            @php
                $maxSoldQuantity = max(1, (int) $topProducts->max('total_quantity'));
            @endphp
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Produk Terlaris</h2>
                    <p class="mt-1 text-sm text-slate-500">Berdasarkan kuantitas dari order lunas.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Top 5</span>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($topProducts as $product)
                    @php
                        $quantityPercent = max(5, (int) round(((int) $product->total_quantity / $maxSoldQuantity) * 100));
                    @endphp
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $product->product_name }}</p>
                                <p class="mt-1 text-xs text-slate-500">Revenue Rp {{ number_format((int) $product->total_revenue, 0, ',', '.') }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ number_format((int) $product->total_quantity) }} terjual</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-slate-900" style="width: {{ $quantityPercent }}%;"></div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">Belum ada produk terjual dari order lunas.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Order Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500 hover:text-slate-900">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-3 py-3">Order</th>
                            <th class="px-3 py-3">Customer</th>
                            <th class="px-3 py-3">Item</th>
                            <th class="px-3 py-3">Total</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 font-semibold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-3 py-3 text-slate-600">{{ $order->customer_name }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $order->items_count }}</td>
                                <td class="px-3 py-3 font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">
                                        {{ \App\Models\Order::statusLabel($order->status) }}
                                    </span>
                                    <p class="mt-1 text-[10px] uppercase tracking-[0.12em] text-slate-500">{{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}</p>
                                </td>
                                <td class="px-3 py-3">
                                    @if ($order->isPaid())
                                        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn-outline px-3 py-2 text-[10px]">PDF</a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-slate-500">Belum ada order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Payment Status</h2>
                <div class="mt-4 space-y-3">
                    @foreach (['paid', 'pending', 'failed', 'expired'] as $status)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $status }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ (int) ($salesByStatus[$status] ?? 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Info Operasional</h2>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-slate-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Member</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($memberCount) }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500">Produk</p>
                        <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($productCount) }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-amber-700">Pending</p>
                        <p class="mt-1 text-xl font-bold text-amber-700">{{ number_format($pendingOrders) }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 px-3 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-700">Lunas</p>
                        <p class="mt-1 text-xl font-bold text-emerald-700">{{ number_format($paidOrderCount) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-lg font-bold text-slate-900">Low Stock Alert</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($lowStockProducts as $product)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                            <span class="text-sm text-slate-700">{{ $product->name }}</span>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-amber-700">
                                Stok {{ $product->stock }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Semua stok masih aman.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
