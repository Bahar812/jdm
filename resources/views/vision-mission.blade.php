@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="vision_mission" />

        <main>
            <section class="relative bg-[color:var(--brand-red)] text-white">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="max-w-4xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/80 sm:tracking-[0.4em]">Visi & Misi</p>
                        <h1 class="mt-4 break-words font-display text-5xl uppercase leading-[0.96] md:text-7xl">Vision & Mission</h1>
                        <p class="mt-6 text-sm leading-relaxed text-white/85 md:text-base">
                            Komitmen kami untuk menjadi penyedia makanan halal, bermutu, dan praktis yang dapat diandalkan
                            oleh seluruh mitra usaha.
                        </p>
                    </div>
                </div>
            </section>

            <section class="relative">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-16">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-8 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <h2 class="font-display text-4xl uppercase text-[color:var(--brand-red)]">Visi</h2>
                            <div class="mt-5 space-y-4 text-sm text-slate-700">
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

                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-8 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <h2 class="font-display text-4xl uppercase text-[color:var(--brand-red)]">Misi</h2>
                            <div class="mt-5 space-y-4 text-sm text-slate-700">
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
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
