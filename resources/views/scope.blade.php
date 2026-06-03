@extends('layouts.app')

@section('seo_title', 'Ruang Lingkup Supplier Frozen Food | CV. Juragan Daging Morowali')
@section('seo_description', 'Ruang lingkup layanan CV. Juragan Daging Morowali meliputi pengadaan frozen food, cold-chain handling, dan distribusi untuk mitra usaha.')
@section('seo_url', route('scope'))

@section('content')
    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="scope" />

        <main>
            <section class="relative">
                <div class="mx-auto max-w-6xl px-6 py-16 md:py-20">
                    <div class="max-w-4xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                            Ruang Lingkup Pekerjaan
                        </p>
                        <h1 class="mt-4 font-display text-5xl uppercase leading-[0.96] text-[color:var(--brand-ink)] md:text-7xl">
                            Scope of Work
                        </h1>
                        <p class="mt-6 text-base leading-relaxed text-slate-700 md:text-lg">
                            Perusahaan kami menyediakan berbagai macam frozen food dengan kualitas terbaik untuk memenuhi
                            kebutuhan usaha kuliner, retail, katering, hingga proyek.
                        </p>
                    </div>
                </div>
            </section>

            <section class="relative">
                <div class="mx-auto max-w-6xl px-6 pb-16">
                    <div class="grid gap-6 md:grid-cols-3">
                        <article class="rounded-3xl border border-red-100/70 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Bahasa Indonesia</p>
                            <p class="mt-4 text-sm text-slate-600">
                                Menyediakan berbagai macam frozen food berkualitas terbaik untuk kebutuhan bisnis Anda.
                            </p>
                        </article>
                        <article class="rounded-3xl border border-red-100/70 bg-[color:var(--brand-red)] p-6 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Core Delivery</p>
                            <p class="mt-4 text-sm text-white/85">
                                Reliable procurement, cold-chain handling, and on-time distribution for business partners.
                            </p>
                        </article>
                        <article class="rounded-3xl border border-red-100/70 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">English</p>
                            <p class="mt-4 text-sm text-slate-600">
                                Our company provides various kinds of frozen food with the best quality.
                            </p>
                        </article>
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
