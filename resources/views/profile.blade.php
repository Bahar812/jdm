@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="profile" />

        <main>
            <section class="relative">
                <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 md:py-20">
                    <div class="max-w-4xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[color:var(--brand-red)] sm:tracking-[0.4em]">
                            Company Profile
                        </p>
                        <h1 class="mt-4 break-words font-display text-5xl uppercase leading-[0.96] text-[color:var(--brand-ink)] md:text-7xl">
                            Tentang Perusahaan
                        </h1>
                        <p class="mt-6 text-base leading-relaxed text-slate-700 md:text-lg">
                            CV. Juragan Daging Morowali adalah perusahaan General Supplier yang fokus menyediakan frozen
                            food untuk wilayah Morowali dan sekitarnya. Kami menjaga proses seleksi produk, penyimpanan,
                            dan distribusi agar kualitas tetap prima hingga ke tangan mitra.
                        </p>
                    </div>
                </div>
            </section>

            <section class="relative">
                <div class="mx-auto max-w-6xl px-4 pb-16 sm:px-6">
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Keunggulan</p>
                            <h2 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Kualitas Terjaga</h2>
                            <p class="mt-3 text-sm text-slate-600">Standar kualitas dan kebersihan dijaga untuk setiap kategori produk.</p>
                        </article>
                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Fokus</p>
                            <h2 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Mitra Usaha</h2>
                            <p class="mt-3 text-sm text-slate-600">Menyediakan kebutuhan mitra usaha dengan harga pantas dan layanan ramah.</p>
                        </article>
                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Rantai Dingin</p>
                            <h2 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Stabil</h2>
                            <p class="mt-3 text-sm text-slate-600">Menjaga suhu penyimpanan agar kualitas produk tetap segar dan aman.</p>
                        </article>
                        <article class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Layanan</p>
                            <h2 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Responsif</h2>
                            <p class="mt-3 text-sm text-slate-600">Komunikasi cepat untuk memastikan kebutuhan bisnis terpenuhi.</p>
                        </article>
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
