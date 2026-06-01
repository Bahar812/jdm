@extends('layouts.admin')

@section('admin_kicker', 'Order')
@section('admin_title', 'Detail Order')

@section('admin_content')
    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $order->order_number }}</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $order->customer_name }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $order->customer_phone }} | {{ $order->customer_email ?: '-' }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $order->shipping_address }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-700">
                            {{ \App\Models\Order::statusLabel($order->status) }}
                        </span>
                        <span class="rounded-full {{ $order->payment_status === \App\Models\Order::PAYMENT_PAID ? 'bg-emerald-100 text-emerald-700' : (in_array($order->payment_status, [\App\Models\Order::PAYMENT_FAILED, \App\Models\Order::PAYMENT_EXPIRED], true) ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                            {{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}
                        </span>
                    </div>
                </div>
                @if ($order->isPaid())
                    <a href="{{ route('admin.orders.invoice', $order) }}" class="admin-btn-neutral px-4 py-2 text-[10px]">
                        Download Invoice PDF
                    </a>
                @endif
            </div>

            <div class="mt-5 space-y-3">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 px-4 py-3">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item->product_name }}</p>
                            <p class="text-xs uppercase tracking-[0.15em] text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="rounded-2xl border border-slate-200 bg-white p-5">
            <h3 class="text-lg font-bold text-slate-900">Update Status</h3>
            <p class="mt-1 text-sm text-slate-600">Total: <span class="font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
            <p class="mt-2 text-xs text-slate-500">Alur pengiriman bertahap: <span class="font-semibold text-slate-700">Diproses -> Dikirim -> Selesai</span></p>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="status">Status Order</label>
                    <select id="status" name="status" class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm">
                        @foreach ($orderStatusOptions as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ \App\Models\Order::statusLabel($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="payment_status">Status Pembayaran</label>
                    <select id="payment_status" name="payment_status" class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm">
                        @foreach ($paymentStatusOptions as $paymentStatus)
                            <option value="{{ $paymentStatus }}" @selected(old('payment_status', $order->payment_status) === $paymentStatus)>{{ \App\Models\Order::paymentStatusLabel($paymentStatus) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="notes">Catatan</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('notes', $order->notes) }}</textarea>
                </div>
                <button class="admin-btn-save w-full justify-center" type="submit">Simpan Perubahan</button>
            </form>
        </aside>
    </div>

    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
        <h3 class="text-lg font-bold text-slate-900">Histori Perubahan Status</h3>
        <p class="mt-1 text-sm text-slate-600">Riwayat sinkronisasi status order dan aktivitas transaksi.</p>

        <div class="mt-5 space-y-3">
            @forelse ($order->statusHistories as $history)
                <div class="rounded-xl border border-slate-100 px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">
                            {{ $history->created_at?->format('d M Y H:i') }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">{{ $history->source }}</span>
                            <span class="rounded-full bg-blue-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-blue-700">{{ $history->changedBy?->name ?? 'System' }}</span>
                        </div>
                    </div>
                    <div class="mt-2 grid gap-2 text-xs text-slate-600 md:grid-cols-2">
                        <p>Status Order: <span class="font-semibold text-slate-900">{{ \App\Models\Order::statusLabel((string) ($history->from_status ?: $history->to_status)) }}</span> -> <span class="font-semibold text-slate-900">{{ \App\Models\Order::statusLabel((string) $history->to_status) }}</span></p>
                        <p>Status Bayar: <span class="font-semibold text-slate-900">{{ \App\Models\Order::paymentStatusLabel((string) ($history->from_payment_status ?: $history->to_payment_status)) }}</span> -> <span class="font-semibold text-slate-900">{{ \App\Models\Order::paymentStatusLabel((string) $history->to_payment_status) }}</span></p>
                    </div>
                    @if ($history->note)
                        <p class="mt-2 text-xs text-slate-500">{{ $history->note }}</p>
                    @endif
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">Belum ada histori perubahan status.</p>
            @endforelse
        </div>
    </section>

    <script>
        (function () {
            const statusSelect = document.getElementById('status');
            const paymentSelect = document.getElementById('payment_status');
            const currentStatus = @json($order->status);

            if (!statusSelect || !paymentSelect) {
                return;
            }

            const isAllowedStatus = (targetStatus, nextPaymentStatus) => {
                if (targetStatus === currentStatus) {
                    return true;
                }

                if (nextPaymentStatus !== 'paid' && ['processing', 'shipped', 'completed'].includes(targetStatus)) {
                    return false;
                }

                if (['completed', 'cancelled'].includes(currentStatus)) {
                    return false;
                }

                if (targetStatus === 'processing') {
                    return currentStatus === 'pending';
                }

                if (targetStatus === 'shipped') {
                    return currentStatus === 'processing';
                }

                if (targetStatus === 'completed') {
                    return currentStatus === 'shipped';
                }

                if (targetStatus === 'cancelled') {
                    return ['pending', 'processing'].includes(currentStatus);
                }

                return false;
            };

            const refreshStatusOptions = () => {
                const nextPaymentStatus = paymentSelect.value;
                let hasSelectedEnabledOption = false;

                Array.from(statusSelect.options).forEach((option) => {
                    const enabled = isAllowedStatus(option.value, nextPaymentStatus);
                    option.disabled = !enabled;

                    if (enabled && option.value === statusSelect.value) {
                        hasSelectedEnabledOption = true;
                    }
                });

                if (!hasSelectedEnabledOption) {
                    const firstEnabled = Array.from(statusSelect.options).find((option) => !option.disabled);
                    if (firstEnabled) {
                        statusSelect.value = firstEnabled.value;
                    }
                }
            };

            paymentSelect.addEventListener('change', refreshStatusOptions);
            refreshStatusOptions();
        })();
    </script>
@endsection
