@extends('layouts.app')

@section('content')
    @php
        $homeCms = $homeCms ?? [];
        $homeCmsDefaults = $homeCmsDefaults ?? [];

        $cms = function (string $key, string $default = '') use ($homeCms, $homeCmsDefaults): string {
            if (array_key_exists($key, $homeCms)) {
                return (string) $homeCms[$key];
            }

            if (array_key_exists($key, $homeCmsDefaults)) {
                return (string) $homeCmsDefaults[$key];
            }

            return $default;
        };

        $cmsImage = function (string $key) use ($cms, $homeCmsDefaults): string {
            $value = trim($cms($key));

            return $value !== '' ? $value : (string) ($homeCmsDefaults[$key] ?? '');
        };

        $cmsParagraphs = function (string $key) use ($cms) {
            return collect(preg_split('/\R{2,}/', trim($cms($key))))
                ->map(fn (string $paragraph): string => trim($paragraph))
                ->filter()
                ->values();
        };

        $focusItems = collect(range(1, 5))->map(fn (int $number): array => [
            'title' => $cms("products_slide_{$number}_title"),
            'meta' => $cms("products_slide_{$number}_meta"),
            'description' => $cms("products_slide_{$number}_description"),
            'image' => $cmsImage("products_slide_{$number}_image"),
        ]);

        $advantageItems = collect(range(1, 3))->map(fn (int $number): array => [
            'title' => $cms("advantages_card_{$number}_title"),
            'description' => $cms("advantages_card_{$number}_description"),
            'image' => $cmsImage("advantages_card_{$number}_image"),
        ]);

        $testimonialColumns = collect(range(1, 9))
            ->map(fn (int $number): array => [
                'text' => $cms("testimonial_{$number}_text"),
                'name' => $cms("testimonial_{$number}_name"),
                'role' => $cms("testimonial_{$number}_role"),
                'image' => $cmsImage("testimonial_{$number}_image"),
            ])
            ->chunk(3)
            ->values();

        $galleryItems = collect(range(1, 5))->map(fn (int $number): array => [
            'image' => $cmsImage("gallery_image_{$number}"),
            'badge' => $cms("gallery_badge_{$number}"),
        ]);

        $instagramItems = collect(range(1, 6))->map(fn (int $number): array => [
            'link' => $cms("instagram_post_{$number}_link"),
            'image' => $cmsImage("instagram_post_{$number}_image"),
        ]);

        $contactEmail = $cms('contact_email');
        $contactPhone = $cms('contact_phone');
        $contactDigits = preg_replace('/\D+/', '', $contactPhone);
        $whatsappNumber = str_starts_with($contactDigits, '0')
            ? '62'.substr($contactDigits, 1)
            : $contactDigits;
    @endphp

    <div class="home-page relative">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.14]"></div>

        <x-navbar active-page="home" />

        <main>
            <section id="profile" class="relative border-y border-red-100/70 bg-white text-slate-950">
                <div class="mx-auto grid w-full max-w-6xl grid-cols-1 items-center gap-8 px-4 py-14 sm:px-6 md:grid-cols-2 md:px-8 md:py-20">
                    <div class="about-content">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-[color:var(--brand-red)] md:text-sm">
                            {{ $cms('profile_kicker') }}
                        </p>
                        <h2 class="mt-4 max-w-xl font-display text-[clamp(2.55rem,6vw,4.8rem)] uppercase leading-[0.95] text-black">
                            {{ $cms('profile_title') }}
                        </h2>
                        <p class="my-5 max-w-xl text-base leading-relaxed text-slate-600 md:my-6 md:text-lg">
                            {{ $cms('profile_description') }}
                        </p>
                        <a class="btn-primary justify-center" href="{{ route('profile') }}">{{ $cms('profile_button_label') }}</a>
                    </div>

                    <div class="about-gallery-grid" aria-label="Galeri produk CV. Juragan Daging Morowali">
                        <figure class="about-gallery-cell about-gallery-cell-1">
                            <img
                                src="{{ $cmsImage('profile_image_1') }}"
                                alt="Daging beku berkualitas"
                                loading="lazy"
                            >
                        </figure>
                        <figure class="about-gallery-cell about-gallery-cell-2">
                            <img
                                src="{{ $cmsImage('profile_image_2') }}"
                                alt="Produk daging segar beku"
                                loading="lazy"
                            >
                        </figure>
                        <figure class="about-gallery-cell about-gallery-cell-3">
                            <img
                                src="{{ $cmsImage('profile_image_3') }}"
                                alt="Produk ayam beku"
                                loading="lazy"
                            >
                        </figure>
                        <figure class="about-gallery-cell about-gallery-cell-4">
                            <img
                                src="{{ $cmsImage('profile_image_4') }}"
                                alt="Ikan segar beku"
                                loading="lazy"
                            >
                        </figure>
                    </div>
                </div>
            </section>

            <section id="produk" class="product-focus-section relative overflow-hidden border-y border-red-100/70 bg-slate-50 text-slate-950">
                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(229,9,20,0.08),transparent_42%)]"></div>
                <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="mb-10 text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[color:var(--brand-red)] md:text-sm">
                            {{ $cms('products_kicker') }}
                        </p>
                        <h2 class="mt-4 font-display text-[clamp(2.8rem,6vw,5.4rem)] uppercase leading-[0.92] text-black">
                            {{ $cms('products_title') }}
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-slate-600 md:text-base">
                            {{ $cms('products_description') }}
                        </p>
                    </div>

                    <div class="focus-rail" data-focus-rail tabindex="0" aria-label="Carousel produk dan layanan">
                        <div class="focus-rail-bg" aria-hidden="true"></div>

                        <div class="focus-rail-stage" data-focus-stage>
                            @foreach ($focusItems as $focusItem)
                                <article
                                    class="focus-rail-card"
                                    data-focus-card
                                    data-title="{{ $focusItem['title'] }}"
                                    data-meta="{{ $focusItem['meta'] }}"
                                    data-description="{{ $focusItem['description'] }}"
                                    data-image="{{ $focusItem['image'] }}"
                                >
                                    <img src="{{ $focusItem['image'] }}" alt="{{ $focusItem['title'] }}" loading="lazy">
                                </article>
                            @endforeach
                        </div>

                        <div class="focus-rail-footer">
                            <div class="focus-rail-info" aria-live="polite">
                                <p class="focus-rail-meta" data-focus-meta>{{ $focusItems->first()['meta'] ?? '' }}</p>
                                <h3 class="focus-rail-title" data-focus-title>{{ $focusItems->first()['title'] ?? '' }}</h3>
                                <p class="focus-rail-description" data-focus-description>
                                    {{ $focusItems->first()['description'] ?? '' }}
                                </p>
                            </div>

                            <div class="focus-rail-actions">
                                <div class="focus-rail-controls">
                                    <button class="focus-rail-button" type="button" data-focus-prev aria-label="Produk sebelumnya">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m15 18-6-6 6-6"></path>
                                        </svg>
                                    </button>
                                    <span class="focus-rail-counter" data-focus-counter>1 / {{ $focusItems->count() }}</span>
                                    <button class="focus-rail-button" type="button" data-focus-next aria-label="Produk berikutnya">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                    </button>
                                </div>
                                <a class="focus-rail-explore" href="{{ route('products') }}">
                                    Explore
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 17 17 7"></path>
                                        <path d="M7 7h10v10"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="ready-stock-section relative overflow-hidden border-b border-red-100/70 bg-white text-slate-950">
                <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-display text-5xl uppercase leading-none text-black sm:text-6xl">
                            {{ $cms('advantages_title') }}
                        </h2>
                        <p class="mt-5 text-base leading-relaxed text-slate-700 md:text-lg">
                            {{ $cms('advantages_description') }}
                        </p>
                    </div>

                    <div class="ready-stock-grid mt-12" aria-label="Keunggulan stok dan layanan">
                        @foreach ($advantageItems as $advantageItem)
                            <article class="ready-stock-card">
                                <img
                                    src="{{ $advantageItem['image'] }}"
                                    alt="{{ $advantageItem['title'] }}"
                                    loading="lazy"
                                >
                                <div class="ready-stock-card-content">
                                    <h3>{{ $advantageItem['title'] }}</h3>
                                    <p>{{ $advantageItem['description'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="visi-misi" class="relative overflow-hidden border-y border-red-100/70 bg-white text-slate-950">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">{{ $cms('vision_kicker') }}</p>
                        <h2 class="mt-3 font-display text-[clamp(2.5rem,5vw,4.8rem)] uppercase leading-[0.95] text-black">
                            {{ $cms('vision_title') }}
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-slate-600 md:text-base">
                            {{ $cms('vision_description') }}
                        </p>
                    </div>

                    <div class="vision-agency-layout mt-12">
                        <figure class="vision-agency-image vision-agency-image-main">
                            <img
                                class="h-full w-full object-cover"
                                src="{{ $cmsImage('vision_main_image') }}"
                                alt="Produk daging berkualitas CV. Juragan Daging Morowali"
                                loading="lazy"
                            >
                        </figure>

                        <div class="vision-agency-badge">
                            <span class="vision-agency-badge-number">{{ $cms('vision_badge_number') }}</span>
                            <span>
                                <strong>{{ $cms('vision_badge_title') }}</strong>
                                <small>{{ $cms('vision_badge_subtitle') }}</small>
                            </span>
                        </div>

                        <div class="vision-agency-panel vision-agency-panel-visi">
                                <h3 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">{{ $cms('vision_visi_title') }}</h3>
                                <div class="mt-4 space-y-4 text-sm leading-relaxed text-slate-600">
                                    @if ($cms('vision_visi_description') !== '')
                                        @foreach ($cmsParagraphs('vision_visi_description') as $paragraph)
                                            <p>{!! nl2br(e($paragraph)) !!}</p>
                                        @endforeach
                                    @else
                                    <p>
                                        Menjadikan Perusahaan Penyedia Makanan yang terjamin kehalalannya, bermutu serta
                                        praktis. Menjadi General Supplier yang terdepan dan memberikan yang terbaik bagi
                                        pelanggan.
                                    </p>
                                    <p>成为一家保证清真、品质和实用的食品供应公司。成为领先的总供应商,为客户提供最好的服务。</p>
                                    <p>
                                        To become a food supply company that is guaranteed to be halal, quality and practical.
                                        Become a leading General Supplier and provide the best for customers.
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="vision-agency-panel vision-agency-panel-misi">
                                <h3 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">{{ $cms('vision_misi_title') }}</h3>
                                <div class="mt-4 space-y-4 text-sm leading-relaxed text-slate-600">
                                    @if ($cms('vision_misi_description') !== '')
                                        @foreach ($cmsParagraphs('vision_misi_description') as $paragraph)
                                            <p>{!! nl2br(e($paragraph)) !!}</p>
                                        @endforeach
                                    @else
                                    <p>
                                        Mengembangkan dan mengenalkan produk makanan lokal yang bermutu. Menyediakan seluruh
                                        kebutuhan bagi mitra usaha dengan harga yang pantas dengan kualitas yang terbaik.
                                    </p>
                                    <p>开发和引进本地优质食品。以合理的价格以最好的质量提供商业伙伴的所有需求。</p>
                                    <p>
                                        Develop and introduce quality local food products. Providing all the needs for business
                                        partners at reasonable prices with the best quality.
                                    </p>
                                    @endif
                                </div>
                            </div>
                        <figure class="vision-agency-image vision-agency-image-wide">
                            <img
                                src="{{ $cmsImage('vision_wide_image') }}"
                                alt="Stok produk frozen food siap distribusi"
                                loading="lazy"
                            >
                        </figure>
                    </div>
                </div>
            </section>

            <section id="testimonials" class="testimonial-section relative overflow-hidden border-b border-red-100/70 bg-white text-slate-950">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="mx-auto max-w-3xl text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                            {{ $cms('testimonials_kicker') }}
                        </p>
                        <h2 class="mt-3 font-display text-[clamp(2.5rem,5vw,4.8rem)] uppercase leading-[0.95] text-black">
                            {{ $cms('testimonials_title') }}
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-slate-600 md:text-base">
                            {{ $cms('testimonials_description') }}
                        </p>
                    </div>

                    @php
                        $legacyTestimonialColumns = [
                            [
                                [
                                    'text' => 'Kualitas dagingnya konsisten dan pengirimannya rapi. Sangat membantu operasional dapur kami setiap hari.',
                                    'name' => 'Rina S.',
                                    'role' => 'Pemilik Katering',
                                    'image' => 'https://randomuser.me/api/portraits/women/32.jpg',
                                ],
                                [
                                    'text' => 'Stok frozen food selalu siap saat kami butuh. Komunikasi juga cepat, jadi pemesanan lebih mudah.',
                                    'name' => 'Andi P.',
                                    'role' => 'Owner Warung Makan',
                                    'image' => 'https://randomuser.me/api/portraits/men/45.jpg',
                                ],
                                [
                                    'text' => 'Produk diterima dalam kondisi baik dan tetap beku. Harga cocok untuk kebutuhan usaha skala harian.',
                                    'name' => 'Maya L.',
                                    'role' => 'Mitra Retail',
                                    'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                ],
                            ],
                            [
                                [
                                    'text' => 'Kami terbantu karena pilihan produknya lengkap, mulai dari daging, ayam, ikan, sampai produk olahan.',
                                    'name' => 'Budi H.',
                                    'role' => 'Pengelola Restoran',
                                    'image' => 'https://randomuser.me/api/portraits/men/36.jpg',
                                ],
                                [
                                    'text' => 'Pelayanannya responsif dan pengemasan produknya rapi. Cocok untuk kebutuhan katering acara besar.',
                                    'name' => 'Sari N.',
                                    'role' => 'Vendor Event',
                                    'image' => 'https://randomuser.me/api/portraits/women/21.jpg',
                                ],
                                [
                                    'text' => 'Kualitas produk stabil, jadi kami lebih mudah menjaga standar menu untuk pelanggan.',
                                    'name' => 'Fajar R.',
                                    'role' => 'Chef Operasional',
                                    'image' => 'https://randomuser.me/api/portraits/men/14.jpg',
                                ],
                            ],
                            [
                                [
                                    'text' => 'Pemesanan fleksibel dan timnya membantu menyesuaikan kebutuhan potongan untuk dapur kami.',
                                    'name' => 'Dewi A.',
                                    'role' => 'Pemilik UMKM Kuliner',
                                    'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                ],
                                [
                                    'text' => 'Produk halal dan kualitasnya terjaga. Kami merasa aman menjadikannya pemasok rutin.',
                                    'name' => 'Rahmat T.',
                                    'role' => 'Mitra Distribusi',
                                    'image' => 'https://randomuser.me/api/portraits/men/27.jpg',
                                ],
                                [
                                    'text' => 'Pengiriman aman, barang sesuai pesanan, dan tim cepat memberi update. Sangat praktis untuk usaha.',
                                    'name' => 'Nadia K.',
                                    'role' => 'Owner Kedai',
                                    'image' => 'https://randomuser.me/api/portraits/women/12.jpg',
                                ],
                            ],
                        ];
                    @endphp

                    <div class="testimonial-marquee" aria-label="Testimoni mitra CV. Juragan Daging Morowali">
                        @foreach ($testimonialColumns as $columnIndex => $testimonials)
                            <div class="testimonial-column {{ $columnIndex === 1 ? 'hidden md:block' : '' }} {{ $columnIndex === 2 ? 'hidden lg:block' : '' }}">
                                <div class="testimonial-track">
                                    @for ($repeat = 0; $repeat < 2; $repeat++)
                                        @foreach ($testimonials as $testimonial)
                                            <article class="testimonial-card">
                                                <p>{{ $testimonial['text'] }}</p>
                                                <div class="testimonial-author">
                                                    <img
                                                        src="{{ $testimonial['image'] }}"
                                                        alt="{{ $testimonial['name'] }}"
                                                        loading="lazy"
                                                    >
                                                    <div>
                                                        <strong>{{ $testimonial['name'] }}</strong>
                                                        <span>{{ $testimonial['role'] }}</span>
                                                    </div>
                                                </div>
                                            </article>
                                        @endforeach
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="relative border-t border-red-100/70 bg-white">
                <div class="mx-auto max-w-6xl px-4 pb-8 pt-14 text-center sm:px-6 md:pt-20">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[color:var(--brand-red)] md:text-sm">
                        {{ $cms('gallery_kicker') }}
                    </p>
                    <h2 class="mt-4 font-display text-[clamp(2.6rem,6vw,5rem)] uppercase leading-[0.92] text-black">
                        {{ $cms('gallery_title') }}
                    </h2>
                </div>
            </section>

            <section id="hero-gallery" class="hero-scroll relative bg-white" data-hero-scroll>
                <div class="hero-scroll-sticky">
                    <div class="hero-bento" aria-hidden="true">
                        @foreach ($galleryItems as $galleryItem)
                            <figure class="hero-cell">
                                <img src="{{ $galleryItem['image'] }}" alt="" loading="lazy">
                                <span class="hero-cell-badge">{{ $galleryItem['badge'] }}</span>
                            </figure>
                        @endforeach
                    </div>
                    <div class="hero-scroll-scrim"></div>
                </div>
            </section>

            <section id="artikel" class="article-section relative border-y border-red-100/70 bg-white text-slate-950">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    @php
                        $articleItems = collect($homeArticles ?? [])->values();

                        if ($articleItems->isEmpty()) {
                            $articleItems = collect([
                                [
                                    'title' => 'Cara Menjaga Kualitas Frozen Food dari Gudang hingga Dapur Mitra',
                                    'category' => 'Artikel',
                                    'image_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1400&q=80',
                                    'url' => route('articles.index'),
                                ],
                                [
                                    'title' => 'Tips Memilih Daging Beku Berkualitas untuk Usaha Kuliner',
                                    'category' => 'Tips',
                                    'image_url' => 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=600&q=80',
                                    'url' => route('articles.index'),
                                ],
                                [
                                    'title' => 'Kebutuhan Ayam Beku untuk Katering, Restoran, dan Retail',
                                    'category' => 'Produk',
                                    'image_url' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=600&q=80',
                                    'url' => route('articles.index'),
                                ],
                                [
                                    'title' => 'Pentingnya Rantai Dingin dalam Pengiriman Produk Frozen',
                                    'category' => 'Distribusi',
                                    'image_url' => 'https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=600&q=80',
                                    'url' => route('articles.index'),
                                ],
                                [
                                    'title' => 'Strategi Menyiapkan Stok Frozen Food untuk Permintaan Harian',
                                    'category' => 'Bisnis',
                                    'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=600&q=80',
                                    'url' => route('articles.index'),
                                ],
                            ]);
                        }

                        $featuredArticle = $articleItems->first();
                        $sideArticles = $articleItems->skip(1)->take(4);
                    @endphp

                    <div class="article-section-header">
                        <h2 class="font-display text-[clamp(2.8rem,6vw,5rem)] uppercase leading-none text-black">
                            {{ $cms('articles_title') }}
                        </h2>
                        <a class="article-more-link" href="{{ route('articles.index') }}">
                            {{ $cms('articles_more_label') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="article-layout">
                        @if ($featuredArticle)
                            <article class="article-featured-card">
                                <a href="{{ $featuredArticle['url'] }}" aria-label="Baca artikel {{ $featuredArticle['title'] }}">
                                    <img
                                        src="{{ $featuredArticle['image_url'] }}"
                                        alt="{{ $featuredArticle['title'] }}"
                                        loading="lazy"
                                    >
                                </a>
                                <div class="article-copy">
                                    <p class="article-category">{{ $featuredArticle['category'] }}</p>
                                    <h3>
                                        <a href="{{ $featuredArticle['url'] }}">
                                            {{ $featuredArticle['title'] }}
                                        </a>
                                    </h3>
                                </div>
                            </article>
                        @endif

                        <div class="article-list" aria-label="Daftar artikel terbaru">
                            @foreach ($sideArticles as $articleItem)
                                <article class="article-list-item">
                                    <a class="article-thumb" href="{{ $articleItem['url'] }}">
                                        <img
                                            src="{{ $articleItem['image_url'] }}"
                                            alt="{{ $articleItem['title'] }}"
                                            loading="lazy"
                                        >
                                    </a>
                                    <div class="article-copy">
                                        <p class="article-category">{{ $articleItem['category'] }}</p>
                                        <h3>
                                            <a href="{{ $articleItem['url'] }}">{{ $articleItem['title'] }}</a>
                                        </h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section id="instagram" class="instagram-section relative overflow-hidden border-b border-red-100/70 bg-slate-50 text-slate-950">
                <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 sm:px-6 md:py-20 lg:grid-cols-[0.78fr_1.22fr]">
                    <div class="instagram-copy">
                        <p class="text-xs font-semibold uppercase tracking-[0.55em] text-slate-500">
                            {{ $cms('instagram_kicker') }}
                        </p>
                        <h2 class="mt-6 max-w-xl font-display text-[clamp(2.6rem,5vw,4.8rem)] uppercase leading-[0.95] text-black">
                            {{ $cms('instagram_title') }}
                        </h2>
                        <p class="mt-6 max-w-md text-sm leading-relaxed text-slate-600 md:text-base">
                            {{ $cms('instagram_description') }}
                        </p>
                        <a
                            class="instagram-button"
                            href="{{ $cms('instagram_link') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ $cms('instagram_button_label') }}
                        </a>
                    </div>

                    <div class="instagram-gallery" aria-label="Galeri Instagram CV. Juragan Daging Morowali">
                        @foreach ($instagramItems as $instagramItem)
                            <a class="instagram-tile" href="{{ $instagramItem['link'] }}" target="_blank" rel="noopener noreferrer" aria-label="Buka postingan Instagram Juragan Daging Morowali">
                                <img src="{{ $instagramItem['image'] }}" alt="Postingan Instagram Juragan Daging Morowali" loading="lazy">
                                <span>IG</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="contact" class="relative bg-white text-slate-950">
                <div class="mx-auto max-w-6xl px-4 pb-20 pt-14 sm:px-6 md:pt-16">
                    <div class="mb-10 max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                            {{ $cms('contact_kicker') }}
                        </p>
                        <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em] text-black">
                            {{ $cms('contact_title') }}
                        </h2>
                        <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600">
                            {{ $cms('contact_description') }}
                        </p>
                    </div>

                    <div class="grid gap-8 lg:grid-cols-[0.82fr_1.18fr]">
                        <div class="rounded-3xl border border-red-100/70 bg-slate-50 p-6 shadow-[0_40px_70px_-52px_rgba(125,12,20,0.42)]">
                            <h3 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">{{ $cms('contact_card_title') }}</h3>
                            <div class="mt-5 space-y-4 text-sm text-slate-600">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Email</p>
                                    <p class="mt-1 text-base">{{ $contactEmail }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Telepon</p>
                                    <p class="mt-1 text-base">{{ $contactPhone }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Alamat</p>
                                    <p class="mt-1 text-base">
                                        {{ $cms('contact_address') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 rounded-2xl border border-red-100 bg-[color:var(--brand-red-10)] p-4 text-xs text-slate-600">
                                <p class="font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                    {{ $cms('contact_google_title') }}
                                </p>
                                <p class="mt-2">
                                    {{ $cms('contact_google_description') }}
                                </p>
                            </div>
                            <div class="mt-6 grid gap-3 sm:flex sm:flex-wrap">
                                <a class="btn-primary justify-center" href="mailto:{{ $contactEmail }}">{{ $cms('contact_email_button_label') }}</a>
                                <a class="btn-outline justify-center" href="tel:{{ $contactPhone }}">{{ $cms('contact_phone_button_label') }}</a>
                                <a class="btn-outline justify-center" href="https://wa.me/{{ $whatsappNumber }}">{{ $cms('contact_whatsapp_button_label') }}</a>
                            </div>
                        </div>
                        <div class="overflow-hidden rounded-3xl border border-red-100/70 bg-white shadow-[0_40px_70px_-52px_rgba(125,12,20,0.42)]">
                            <iframe
                                class="h-80 w-full md:h-full md:min-h-[28rem]"
                                src="{{ $cms('contact_map_src') }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi JDM Frozen Food di Google Maps"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        @include('partials.footer')
    </div>
@endsection

