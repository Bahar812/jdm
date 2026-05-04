@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-50">
        <x-navbar active-page="products" />

        <main class="mx-auto max-w-5xl px-6 py-12">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Order</p>
                    <h1 class="mt-2 font-display text-4xl uppercase text-slate-900">{{ $order->order_number }}</h1>
                    <p class="mt-2 text-sm text-slate-600">Status: <span class="font-semibold uppercase">{{ \App\Models\Order::statusLabel($order->status) }}</span> | Pembayaran: <span class="font-semibold uppercase">{{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}</span></p>

                    <div class="mt-6 space-y-3">
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

                <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-display text-3xl uppercase text-slate-900">Pembayaran</h2>
                    <div class="mt-4 rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Total Tagihan</p>
                        <p class="mt-1 text-2xl font-bold text-[color:var(--brand-red)]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-slate-600">
                        <p><span class="font-semibold">Nama:</span> {{ $order->customer_name }}</p>
                        <p><span class="font-semibold">No. HP:</span> {{ $order->customer_phone }}</p>
                        <p><span class="font-semibold">Alamat:</span> {{ $order->shipping_address }}</p>
                    </div>

                    @if ($order->payment_status === \App\Models\Order::PAYMENT_PAID)
                        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            Pembayaran sudah diterima. Order berjalan sesuai alur: diproses -> dikirim -> selesai.
                        </div>
                    @elseif ($order->midtrans_snap_token && $clientKey)
                        <button id="pay-button" class="btn-primary mt-6 w-full justify-center" type="button">Bayar Sekarang</button>
                        <p class="mt-3 text-xs text-slate-500">Setelah pembayaran sukses, status order akan otomatis berubah.</p>
                    @else
                        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                            Midtrans belum terkonfigurasi. Isi MIDTRANS_CLIENT_KEY dan MIDTRANS_SERVER_KEY di file .env.
                        </div>
                    @endif

                    <a class="btn-outline mt-4 w-full justify-center" href="{{ route('products') }}">Belanja Lagi</a>
                </aside>
            </div>
        </main>
    </div>

    @if ($order->midtrans_snap_token && $clientKey && $order->payment_status !== \App\Models\Order::PAYMENT_PAID)
        <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
        <script>
            const snapToken = @json($order->midtrans_snap_token);
            const shouldAutoOpen = @json((bool) session('open_midtrans_popup'));
            let isSnapOpen = false;

            const openSnapPopup = function () {
                if (isSnapOpen || !window.snap || !snapToken) {
                    return;
                }

                isSnapOpen = true;
                window.snap.pay(snapToken, {
                    onSuccess: function () {
                        window.location.reload();
                    },
                    onPending: function () {
                        window.location.reload();
                    },
                    onClose: function () {
                        isSnapOpen = false;
                    },
                    onError: function () {
                        isSnapOpen = false;
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    }
                });
            };

            document.getElementById('pay-button')?.addEventListener('click', openSnapPopup);

            if (shouldAutoOpen) {
                window.setTimeout(openSnapPopup, 350);
            }
        </script>
    @endif
@endsection
