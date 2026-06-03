@extends('layouts.app')

@section('seo_title', 'Visi & Misi | CV. Juragan Daging Morowali')
@section('seo_description', 'Visi dan misi CV. Juragan Daging Morowali dalam menyediakan produk frozen food yang halal, bermutu, praktis, dan terpercaya untuk mitra usaha.')
@section('seo_url', route('vision_mission'))

@section('content')
    @php
        $visuals = [
            [
                'src' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1200&q=80',
                'alt' => 'Potongan daging segar untuk produk frozen food',
            ],
            [
                'src' => 'https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=900&q=80',
                'alt' => 'Daging sapi beku berkualitas',
            ],
            [
                'src' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=900&q=80',
                'alt' => 'Produk ayam beku siap olah',
            ],
        ];

        $principles = [
            ['title' => 'Halal', 'description' => 'Produk dipilih dari sumber yang menjaga standar kehalalan dan keamanan pangan.'],
            ['title' => 'Bermutu', 'description' => 'Kualitas produk, warna, tekstur, dan kemasan diperhatikan sebelum dikirim ke mitra.'],
            ['title' => 'Praktis', 'description' => 'Stok dan layanan dibuat mudah untuk mendukung kebutuhan dapur usaha setiap hari.'],
        ];
    @endphp

    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="vision_mission" />

        <main>
            <section class="relative">
                <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 md:py-18 lg:grid-cols-[0.92fr_1.08fr]">
                    <div class="relative z-10">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[color:var(--brand-red)] sm:tracking-[0.4em]">Visi & Misi</p>
                        <h1 class="mt-4 break-words font-display text-5xl uppercase leading-[0.96] text-[color:var(--brand-ink)] md:text-7xl">
                            Vision & Mission
                        </h1>
                        <p class="mt-6 max-w-2xl text-sm leading-relaxed text-slate-700 md:text-base">
                            Komitmen kami untuk menjadi penyedia makanan halal, bermutu, dan praktis yang dapat diandalkan
                            oleh seluruh mitra usaha.
                        </p>
                        <div class="mt-7 flex flex-wrap gap-3">
                            @foreach ($principles as $principle)
                                <span class="rounded-full border border-red-100 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-[color:var(--brand-red)] shadow-sm">
                                    {{ $principle['title'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-5 top-10 hidden h-28 w-28 rounded-full border-[18px] border-[color:var(--brand-red-10)] md:block"></div>
                        <div class="relative grid gap-4 sm:grid-cols-[0.9fr_1.1fr]">
                            <figure class="relative overflow-hidden rounded-3xl border-8 border-white shadow-[0_28px_70px_-35px_rgba(125,12,20,0.7)] sm:mt-12">
                                <img class="h-72 w-full object-cover sm:h-96" src="{{ $visuals[1]['src'] }}" alt="{{ $visuals[1]['alt'] }}">
                            </figure>
                            <div class="space-y-4">
                                <figure class="overflow-hidden rounded-[2rem] border-8 border-white shadow-[0_28px_70px_-35px_rgba(125,12,20,0.7)]">
                                    <img class="h-52 w-full object-cover sm:h-64" src="{{ $visuals[0]['src'] }}" alt="{{ $visuals[0]['alt'] }}">
                                </figure>
                                <div class="rounded-3xl bg-[color:var(--brand-red)] p-5 text-white shadow-[0_22px_50px_-28px_rgba(125,12,20,0.95)]">
                                    <p class="font-display text-5xl uppercase leading-none">100%</p>
                                    <p class="mt-2 text-sm font-bold">Halal & Bermutu</p>
                                    <p class="mt-1 text-xs text-white/75">General Supplier Frozen Food</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative">
                <div class="mx-auto max-w-6xl px-4 pb-16 sm:px-6 md:pb-20">
                    <div class="grid gap-6 lg:grid-cols-[0.82fr_1.18fr]">
                        <aside class="relative overflow-hidden rounded-3xl border border-red-100 bg-white p-4 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <img class="h-full min-h-[26rem] w-full rounded-[1.25rem] object-cover" src="{{ $visuals[2]['src'] }}" alt="{{ $visuals[2]['alt'] }}">
                            <div class="absolute inset-x-8 bottom-8 rounded-2xl bg-white/92 p-5 shadow-xl backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--brand-red)]">Komitmen Kami</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700">
                                    Menjaga kualitas produk dan layanan agar mitra usaha dapat beroperasi dengan lebih tenang.
                                </p>
                            </div>
                        </aside>

                        <div class="grid gap-6">
                            <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)] md:p-8">
                                <div class="flex items-start gap-4">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[color:var(--brand-red)] font-display text-2xl uppercase text-white">V</span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Vision</p>
                                        <h2 class="mt-1 font-display text-4xl uppercase text-[color:var(--brand-red)]">Visi</h2>
                                    </div>
                                </div>
                                <div class="mt-5 space-y-4 text-sm leading-7 text-slate-700">
                                    <p>
                                        Menjadikan perusahaan penyedia makanan yang terjamin kehalalannya, bermutu, serta praktis.
                                        Menjadi General Supplier terdepan dan memberikan yang terbaik bagi pelanggan.
                                    </p>
                                    <p>
                                        To become a food supply company that is guaranteed to be halal, quality, and practical.
                                        Become a leading general supplier and provide the best for customers.
                                    </p>
                                </div>
                            </article>

                            <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)] md:p-8">
                                <div class="flex items-start gap-4">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[color:var(--brand-ink)] font-display text-2xl uppercase text-white">M</span>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Mission</p>
                                        <h2 class="mt-1 font-display text-4xl uppercase text-[color:var(--brand-red)]">Misi</h2>
                                    </div>
                                </div>
                                <div class="mt-5 space-y-4 text-sm leading-7 text-slate-700">
                                    <p>
                                        Mengembangkan dan mengenalkan produk makanan lokal yang bermutu. Menyediakan seluruh
                                        kebutuhan bagi mitra usaha dengan harga pantas dan kualitas terbaik.
                                    </p>
                                    <p>
                                        Develop and introduce quality local food products. Provide all business partner needs at
                                        reasonable prices with the best quality.
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 md:grid-cols-3">
                        @foreach ($principles as $principle)
                            <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_30px_60px_-48px_rgba(125,12,20,0.65)]">
                                <p class="font-display text-3xl uppercase text-[color:var(--brand-red)]">{{ $principle['title'] }}</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $principle['description'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
