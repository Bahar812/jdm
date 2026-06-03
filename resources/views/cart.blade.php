@extends('layouts.app')

@section('seo_title', 'Keranjang Belanja | JDM Frozen Food')
@section('seo_description', 'Keranjang belanja produk frozen food JDM.')
@section('seo_robots', 'noindex, nofollow')

@section('content')
    <div class="bg-white">
        <x-navbar active-page="products" />

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 md:py-16">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-8">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--brand-red)] sm:tracking-[0.35em]">Keranjang</p>
                <h1 class="mt-3 break-words font-display text-5xl uppercase text-[color:var(--brand-ink)] md:text-6xl">Checkout Frozen Food</h1>
            </div>

            @if ($items->isEmpty())
                <div class="rounded-3xl border border-red-100/70 bg-white p-10 text-center shadow-[0_30px_70px_-45px_rgba(125,12,20,0.45)]">
                    <p class="mx-auto max-w-2xl text-sm leading-relaxed text-slate-600 md:text-base">
                        Keranjang Anda masih kosong. Silakan pilih produk terlebih dahulu.
                    </p>
                    <div class="mt-8">
                        <a class="btn-primary" href="{{ route('products') }}">Lanjut Pilih Produk</a>
                    </div>
                </div>
            @else
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <section class="space-y-4 rounded-3xl border border-red-100/70 bg-white p-4 shadow-[0_25px_65px_-40px_rgba(125,12,20,0.45)] sm:p-6">
                        @foreach ($items as $item)
                            <article class="flex flex-col gap-4 rounded-2xl border border-slate-100 p-4 md:flex-row md:items-center">
                                <img
                                    class="h-24 w-full rounded-xl object-cover md:w-24"
                                    src="{{ $item['product']->image_url }}"
                                    alt="{{ $item['product']->name }}"
                                >
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $item['product']->category }}</p>
                                    <h2 class="mt-1 break-words font-display text-2xl uppercase text-[color:var(--brand-ink)]">{{ $item['product']->name }}</h2>
                                    <p class="mt-1 text-sm font-semibold text-[color:var(--brand-red)]">
                                        Rp {{ number_format($item['product']->price, 0, ',', '.') }} / {{ $item['product']->unit }}
                                    </p>
                                </div>
                                <div class="grid w-full gap-3 sm:w-auto sm:grid-cols-[auto_auto] md:flex md:items-center">
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            class="h-10 w-16 rounded-lg border border-slate-200 px-2 text-sm"
                                            type="number"
                                            name="quantity"
                                            min="0"
                                            max="{{ $item['product']->stock }}"
                                            value="{{ $item['quantity'] }}"
                                        >
                                        <button class="btn-outline justify-center px-3 py-2 text-[10px]" type="submit">Update</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-outline w-full justify-center px-3 py-2 text-[10px]" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </section>

                    <section class="rounded-3xl border border-red-100/70 bg-white p-4 shadow-[0_25px_65px_-40px_rgba(125,12,20,0.45)] sm:p-6">
                        <h2 class="font-display text-3xl uppercase text-[color:var(--brand-ink)]">Data Pengiriman</h2>
                        <p class="mt-2 text-sm text-slate-600">Isi data berikut untuk proses checkout dan pembayaran Midtrans.</p>

                        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm">
                            <p class="font-semibold text-slate-700">Ringkasan Belanja</p>
                            <div class="mt-3 flex items-center justify-between text-slate-600">
                                <span>Total Item</span>
                                <span>{{ $itemCount }}</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-slate-900">
                                <span class="font-semibold">Subtotal</span>
                                <span class="font-bold text-[color:var(--brand-red)]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="customer_name">Nama</label>
                                <input id="customer_name" name="customer_name" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="customer_email">Email</label>
                                <input id="customer_email" type="email" name="customer_email" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="customer_phone">No. HP</label>
                                <input id="customer_phone" name="customer_phone" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="shipping_address">Alamat Pengiriman</label>
                                <textarea id="shipping_address" name="shipping_address" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm" required>{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="notes">Catatan</label>
                                <textarea id="notes" name="notes" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('notes') }}</textarea>
                            </div>
                            <button class="btn-primary w-full justify-center" type="submit">Buat Order & Bayar</button>
                        </form>
                    </section>
                </div>
            @endif
        </main>

        @include('partials.footer')
    </div>
@endsection
