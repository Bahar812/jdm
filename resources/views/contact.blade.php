@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="contact" />

        <main>
            <section class="relative">
                <div class="mx-auto max-w-6xl px-6 py-16 md:py-20">
                    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                                Hubungi Kami
                            </p>
                            <h1 class="mt-4 font-display text-5xl uppercase tracking-[0.1em] text-[color:var(--brand-ink)] md:text-7xl">
                                Siap Menjadi Mitra Anda
                            </h1>
                            <p class="mt-5 text-base leading-relaxed text-slate-700 md:text-lg">
                                Kami siap memenuhi kebutuhan frozen food Anda dengan kualitas terbaik dan layanan cepat.
                                Hubungi kami untuk informasi produk, harga, dan kemitraan.
                            </p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <a class="btn-primary" href="mailto:juragandagingmorowali@gmail.com">Email Kami</a>
                                <a class="btn-outline" href="tel:+6281215054099">Telepon</a>
                                <a class="btn-outline" href="https://wa.me/6281215054099">WhatsApp</a>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_40px_70px_-45px_rgba(125,12,20,0.5)]">
                            <h2 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">Kontak Utama</h2>
                            <div class="mt-5 space-y-4 text-sm text-slate-600">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Email</p>
                                    <p class="mt-1 text-base">juragandagingmorowali@gmail.com</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Telepon</p>
                                    <p class="mt-1 text-base">0812 1504 5099</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Alamat</p>
                                    <p class="mt-1 text-base">
                                        Desa Sakita, Kecamatan Bungku Tengah, Kabupaten Morowali, Provinsi Sulawesi Tengah
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 rounded-2xl bg-[color:var(--brand-red-10)] p-4 text-xs text-slate-600">
                                <p class="font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                    Working Area
                                </p>
                                <p class="mt-2">
                                    Morowali dan sekitarnya untuk suplai frozen food yang konsisten dan tepat waktu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
