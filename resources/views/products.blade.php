@extends('layouts.app')

@section('content')
    @php
        $categoryCounts = [];
        foreach ($products as $product) {
            $category = $product->category;
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
        }
    @endphp

    <div class="bg-white">

        <x-navbar active-page="products" />

        <main>
            <section class="relative pb-16">
                <div class="mx-auto max-w-7xl px-4 pb-10 pt-12 sm:px-6 md:pb-12 md:pt-20">
                    <div class="max-w-4xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--brand-red)] sm:tracking-[0.4em]">Produk Frozen Food</p>
                        <h1 class="mt-5 break-words font-display text-5xl uppercase leading-[0.95] text-[color:var(--brand-ink)] md:text-7xl">
                            Katalog Produk
                        </h1>
                        <p class="mt-6 text-base leading-relaxed text-slate-700 md:text-lg">
                            Pilih kategori produk di sidebar kiri, lalu gunakan filter dan search untuk menemukan produk
                            yang Anda butuhkan lebih cepat.
                        </p>
                    </div>
                </div>

                <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[250px_1fr]">
                    <aside class="lg:sticky lg:top-28 lg:h-fit">
                        <div class="rounded-2xl border border-red-100/70 bg-white/95 p-5 shadow-[0_20px_50px_-38px_rgba(125,12,20,0.45)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">Kategori</p>
                            <div class="mt-4 space-y-2">
                                <button type="button" class="product-category-btn is-active" data-category-btn data-category="all">
                                    <span>Semua Produk</span>
                                    <span>{{ count($products) }}</span>
                                </button>
                                @foreach ($categoryCounts as $category => $count)
                                    <button type="button" class="product-category-btn" data-category-btn data-category="{{ $category }}">
                                        <span>{{ $category }}</span>
                                        <span>{{ $count }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <div>
                        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-end">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                Showing <span id="results-count">{{ count($products) }}</span> products
                            </p>
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <div class="relative">
                                    <input
                                        id="product-search"
                                        type="search"
                                        class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[color:var(--brand-red)] sm:w-64"
                                        placeholder="Cari produk..."
                                    />
                                </div>
                                <select
                                    id="product-sort"
                                    class="h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none transition focus:border-[color:var(--brand-red)]"
                                >
                                    <option value="default">Urutkan: default</option>
                                    <option value="price-asc">Urutkan dari termurah</option>
                                    <option value="price-desc">Urutkan dari termahal</option>
                                    <option value="name-asc">Urutkan A-Z</option>
                                </select>
                            </div>
                        </div>

                        <div id="product-grid" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                            @foreach ($products as $product)
                                <article
                                    class="product-card h-full overflow-hidden rounded-3xl border border-red-100/70 bg-white shadow-[0_25px_60px_-42px_rgba(125,12,20,0.5)]"
                                    data-product-card
                                    data-category="{{ $product->category }}"
                                    data-name="{{ strtolower($product->name) }}"
                                    data-price="{{ $product->price }}"
                                >
                                    <div class="relative">
                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            <img class="h-52 w-full object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        </a>
                                        <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-[color:var(--brand-red)]">
                                            {{ $product->badge }}
                                        </span>
                                    </div>
                                    <div class="flex h-full flex-col space-y-3 p-5">
                                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $product->category }}</p>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="inline-block">
                                            <h2 class="product-title font-display uppercase leading-none text-[color:var(--brand-ink)]">{{ $product->name }}</h2>
                                        </a>
                                        <div class="mt-1 space-y-3 pt-1">
                                            <p class="product-price font-bold text-[color:var(--brand-red)]">
                                                @if ($product->price > 0)
                                                    Rp {{ number_format($product->price, 0, ',', '.') }} / {{ $product->unit ?: 'unit' }}
                                                @else
                                                    By Request
                                                @endif
                                            </p>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a class="product-detail-btn inline-flex items-center rounded-full border border-[color:var(--brand-red-30)] bg-white font-semibold uppercase text-[color:var(--brand-red)] transition hover:border-[color:var(--brand-red)]" href="{{ route('product.detail', $product->slug) }}">
                                                    Detail
                                                </a>
                                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button class="product-cart-btn inline-flex items-center rounded-full bg-[color:var(--brand-red)] font-semibold uppercase text-white transition hover:bg-[color:var(--brand-red-dark)]" type="submit">
                                                        Add to Cart
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div id="empty-state" class="mt-10 hidden rounded-2xl border border-red-100/70 bg-white/95 p-6 text-center text-sm text-slate-600">
                            Produk tidak ditemukan untuk filter saat ini.
                        </div>
                    </div>
                </div>
            </section>
        </main>
        @include('partials.footer')
    </div>

    <style>
        .product-category-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 0.85rem;
            background: #fff;
            color: #475569;
            padding: 0.65rem 0.8rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: 0.2s ease;
        }

        .product-category-btn:hover {
            border-color: rgba(55, 65, 81, 0.45);
            background: #f8fafc;
            color: #334155;
        }

        .product-category-btn.is-active {
            border-color: #374151;
            background: #374151;
            color: #fff;
        }

        .product-title {
            font-size: 1.55rem;
            line-height: 1.05;
        }

        .product-price {
            font-size: 0.9rem;
            line-height: 1.2;
        }

        .product-cart-btn {
            padding: 0.42rem 0.78rem;
            font-size: 10px;
            letter-spacing: 0.08em;
        }

        .product-detail-btn {
            padding: 0.42rem 0.78rem;
            font-size: 10px;
            letter-spacing: 0.08em;
        }
    </style>

    <script>
        (() => {
            const grid = document.getElementById("product-grid");
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll("[data-product-card]"));
            const categoryButtons = Array.from(document.querySelectorAll("[data-category-btn]"));
            const searchInput = document.getElementById("product-search");
            const sortSelect = document.getElementById("product-sort");
            const resultsCount = document.getElementById("results-count");
            const emptyState = document.getElementById("empty-state");
            let currentCategory = "all";

            const sortCards = () => {
                const sortValue = sortSelect ? sortSelect.value : "default";
                const sorted = [...cards].sort((a, b) => {
                    const aPrice = Number(a.dataset.price);
                    const bPrice = Number(b.dataset.price);
                    const aName = a.dataset.name || "";
                    const bName = b.dataset.name || "";

                    if (sortValue === "price-asc") return aPrice - bPrice;
                    if (sortValue === "price-desc") return bPrice - aPrice;
                    if (sortValue === "name-asc") return aName.localeCompare(bName);
                    return 0;
                });

                sorted.forEach((card) => grid.appendChild(card));
            };

            const applyFilters = () => {
                sortCards();
                const query = (searchInput ? searchInput.value : "").trim().toLowerCase();
                let visible = 0;

                cards.forEach((card) => {
                    const inCategory = currentCategory === "all" || card.dataset.category === currentCategory;
                    const inSearch = !query || (card.dataset.name || "").includes(query);
                    const show = inCategory && inSearch;
                    card.classList.toggle("hidden", !show);
                    if (show) visible += 1;
                });

                if (resultsCount) resultsCount.textContent = String(visible);
                if (emptyState) emptyState.classList.toggle("hidden", visible !== 0);
            };

            categoryButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    currentCategory = button.dataset.category || "all";
                    categoryButtons.forEach((item) => item.classList.remove("is-active"));
                    button.classList.add("is-active");
                    applyFilters();
                });
            });

            if (searchInput) {
                searchInput.addEventListener("input", applyFilters);
            }

            if (sortSelect) {
                sortSelect.addEventListener("change", applyFilters);
            }

            applyFilters();
        })();
    </script>
@endsection
