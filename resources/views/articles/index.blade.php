@extends('layouts.app')

@php
    $articlesSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => 'Berita & Artikel JDM Frozen Food',
        'description' => 'Artikel seputar frozen food, produk bahan baku, dan layanan CV. Juragan Daging Morowali.',
        'url' => route('articles.index'),
        'blogPost' => $articles->getCollection()->take(10)->map(fn ($article): array => [
            '@type' => 'BlogPosting',
            'headline' => $article->title,
            'url' => route('articles.show', $article),
            'image' => $article->image_url ?: asset('images/jdm-logo.png'),
            'datePublished' => $article->published_at?->toAtomString(),
        ])->all(),
    ];
@endphp

@section('seo_title', 'Berita & Artikel Frozen Food | CV. Juragan Daging Morowali')
@section('seo_description', 'Baca artikel JDM Frozen Food tentang produk frozen food, tips bahan baku, layanan supplier, dan informasi terbaru untuk mitra usaha.')
@section('seo_url', $articles->url($articles->currentPage()))
@section('seo_json_ld', json_encode($articlesSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))
@section('seo_head')
    @if ($articles->previousPageUrl())
        <link rel="prev" href="{{ $articles->previousPageUrl() }}">
    @endif
    @if ($articles->nextPageUrl())
        <link rel="next" href="{{ $articles->nextPageUrl() }}">
    @endif
@endsection

@section('content')
    <div class="bg-white text-slate-950">
        <x-navbar active-page="articles" />

        <main>
            <section class="border-b border-red-100/70 bg-white">
                <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 md:py-20">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[color:var(--brand-red)] sm:tracking-[0.45em]">Artikel</p>
                    <div class="mt-5 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div class="max-w-3xl">
                            <h1 class="font-display text-[clamp(3rem,7vw,6rem)] uppercase leading-none text-black">Berita & Artikel</h1>
                            <p class="mt-5 text-base leading-relaxed text-slate-600 md:text-lg">
                                Update informasi produk frozen food, tips memilih bahan baku, dan kabar layanan JDM Frozen Food.
                            </p>
                        </div>
                        <a href="{{ route('home') }}#artikel" class="btn-outline justify-center px-5 py-3 text-[10px]">Kembali</a>
                    </div>
                </div>
            </section>

            <section class="bg-white">
                <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 md:py-16">
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($articles as $article)
                            @php
                                $imageUrl = $article->image_url ?: 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=900&q=80';
                            @endphp
                            <article class="overflow-hidden rounded-2xl border border-red-100/70 bg-white shadow-[0_24px_70px_-50px_rgba(125,12,20,0.55)]">
                                <a href="{{ route('articles.show', $article) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100">
                                    <img class="h-full w-full object-cover transition duration-300 hover:scale-105" src="{{ $imageUrl }}" alt="{{ $article->title }}" loading="lazy">
                                </a>
                                <div class="p-5">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[color:var(--brand-red)]">{{ $article->category }}</p>
                                    <h2 class="mt-3 break-words text-xl font-bold leading-snug text-slate-950">
                                        <a href="{{ route('articles.show', $article) }}" class="hover:text-[color:var(--brand-red)]">{{ $article->title }}</a>
                                    </h2>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">
                                        {{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 140) }}
                                    </p>
                                    <div class="mt-5 flex items-center justify-between gap-3">
                                        <span class="text-xs font-semibold text-slate-400">{{ $article->published_at?->format('d M Y') }}</span>
                                        <a href="{{ route('articles.show', $article) }}" class="text-xs font-bold uppercase tracking-[0.2em] text-[color:var(--brand-red)]">Baca</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-red-100/70 bg-white p-8 text-center text-sm text-slate-600 sm:col-span-2 lg:col-span-3">
                                Artikel belum tersedia.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10">
                        {{ $articles->links() }}
                    </div>
                </div>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
