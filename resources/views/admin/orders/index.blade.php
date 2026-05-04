@extends('layouts.admin')

@section('admin_kicker', 'Order')
@section('admin_title', 'Order Masuk')

@section('admin_content')
    <form method="GET" class="mb-4 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 md:grid-cols-[1fr_180px_180px_auto]">
        <input name="q" value="{{ $search }}" placeholder="Cari order/customer/phone..." class="h-10 rounded-xl border border-slate-200 px-4 text-sm">
        <select name="status" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
            <option value="">Semua Status Order</option>
            @foreach ($orderStatusOptions as $item)
                <option value="{{ $item }}" @selected($status === $item)>{{ \App\Models\Order::statusLabel($item) }}</option>
            @endforeach
        </select>
        <select name="payment" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
            <option value="">Semua Status Bayar</option>
            @foreach ($paymentStatusOptions as $item)
                <option value="{{ $item }}" @selected($payment === $item)>{{ \App\Models\Order::paymentStatusLabel($item) }}</option>
            @endforeach
        </select>
        <button class="btn-outline px-4 py-2 text-[10px]" type="submit">Filter</button>
    </form>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Pembayaran</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            <p>{{ $order->customer_name }}</p>
                            <p class="text-xs">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">{{ \App\Models\Order::statusLabel($order->status) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full {{ $order->payment_status === \App\Models\Order::PAYMENT_PAID ? 'bg-emerald-100 text-emerald-700' : (in_array($order->payment_status, [\App\Models\Order::PAYMENT_FAILED, \App\Models\Order::PAYMENT_EXPIRED], true) ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                {{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline px-3 py-2 text-[10px]">Detail</a>
                                @if ($order->nextFulfillmentStatus())
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $order->nextFulfillmentStatus() }}">
                                        <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                                        <button class="btn-outline px-3 py-2 text-[10px]" type="submit">
                                            {{ \App\Models\Order::statusLabel($order->nextFulfillmentStatus()) }}
                                        </button>
                                    </form>
                                @endif
                                @if ($order->isPaid())
                                    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn-primary px-3 py-2 text-[10px]">Invoice PDF</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada order masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection
