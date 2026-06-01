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
                    <div class="mb-10 max-w-3xl">
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
                    </div>

                    <div class="grid gap-8 lg:grid-cols-[0.82fr_1.18fr]">
                        <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_40px_70px_-45px_rgba(125,12,20,0.5)]">
                            <h2 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">Kontak Utama</h2>
                            <div class="mt-5 space-y-4 text-sm text-slate-600">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Email</p>
                                    <p class="mt-1 text-base">juragandagingmorowali@gmail.com</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Telepon</p>
                                    <p class="mt-1 text-base">0855-2268-888</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Alamat</p>
                                    <p class="mt-1 text-base">
                                        Northwest Boulevard NV 15 No. 26, Citraland, Kec. Pakal, Surabaya, Jawa Timur 60196
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 rounded-2xl bg-[color:var(--brand-red-10)] p-4 text-xs text-slate-600">
                                <p class="font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                    Google Business
                                </p>
                                <p class="mt-2">
                                    JDM Frozen Food, toko makanan beku. Buka sampai pukul 19.00.
                                </p>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <a class="btn-primary" href="mailto:juragandagingmorowali@gmail.com">Email Kami</a>
                                <a class="btn-outline" href="tel:+628552268888">Telepon</a>
                                <a class="btn-outline" href="https://wa.me/628552268888">WhatsApp</a>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-3xl border border-red-100/70 bg-white shadow-[0_40px_70px_-45px_rgba(125,12,20,0.5)]">
                            <iframe
                                class="h-full min-h-[28rem] w-full"
                                src="https://www.google.com/maps?q=JDM%20Frozen%20Food%2C%20Northwest%20Boulevard%20NV%2015%20No.%2026%2C%20Citraland%2C%20Kec.%20Pakal%2C%20Surabaya%2C%20Jawa%20Timur%2060196&output=embed"
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
