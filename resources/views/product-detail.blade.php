@extends('layouts.app')

@section('content')
    <div class="bg-white">
        <x-navbar active-page="products" />

        <main class="pb-16">
            <section class="mx-auto grid max-w-7xl gap-10 px-6 pb-10 pt-12 lg:grid-cols-[1fr_1fr]">
                <div>
                    <img class="h-[24rem] w-full rounded-3xl object-cover md:h-[30rem]" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">{{ $product->category }}</p>
                    <h1 class="mt-4 font-display text-5xl uppercase leading-[0.95] text-[color:var(--brand-ink)] md:text-6xl">
                        {{ $product->name }}
                    </h1>
                    <p class="mt-6 text-sm leading-relaxed text-slate-700 md:text-base">
                        {{ $product->description }}
                    </p>

                    <div class="mt-7 flex items-end gap-3">
                        <p class="text-3xl font-bold text-[color:var(--brand-red)]">
                            @if ($product->price > 0)
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            @else
                                By Request
                            @endif
                        </p>
                        <p class="pb-1 text-sm font-semibold text-slate-500">/{{ $product->unit }}</p>
                    </div>
                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Stok tersedia: {{ $product->stock }}</p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button class="btn-primary" type="submit">+ Keranjang</button>
                        </form>
                        <a class="btn-outline" href="{{ route('cart') }}">Lihat Keranjang</a>
                        <a class="btn-outline" href="{{ route('products') }}">Kembali ke Produk</a>
                    </div>
                </div>
            </section>

            @if ($relatedProducts->count() > 0)
                <section class="mx-auto max-w-7xl px-6">
                    <div class="mb-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">Rekomendasi</p>
                        <h2 class="mt-3 font-display text-4xl uppercase text-[color:var(--brand-ink)]">Produk Terkait</h2>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($relatedProducts as $item)
                            <a href="{{ route('product.detail', $item->slug) }}" class="overflow-hidden rounded-3xl border border-red-100/70 bg-white shadow-[0_25px_60px_-42px_rgba(125,12,20,0.5)]">
                                <img class="h-44 w-full object-cover" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                <div class="p-4">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $item->category }}</p>
                                    <p class="mt-2 font-display text-2xl uppercase leading-none text-[color:var(--brand-ink)]">{{ $item->name }}</p>
                                    <p class="mt-3 text-sm font-bold text-[color:var(--brand-red)]">
                                        @if ($item->price > 0)
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        @else
                                            By Request
                                        @endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        @include('partials.footer')
    </div>
@endsection
