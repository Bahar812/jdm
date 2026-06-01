@extends('layouts.app')

@section('content')
    @php
        $imageUrl = $article->image_url ?: 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=1400&q=80';
    @endphp

    <div class="bg-white text-slate-950">
        <x-navbar active-page="articles" />

        <main>
            <article class="border-b border-red-100/70 bg-white">
                <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 md:py-16">
                    <a href="{{ route('articles.index') }}" class="inline-flex text-xs font-bold uppercase tracking-[0.22em] text-[color:var(--brand-red)]">Kembali ke artikel</a>
                    <p class="mt-8 text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">{{ $article->category }} · {{ $article->published_at?->format('d M Y') }}</p>
                    <h1 class="mt-4 break-words font-display text-[clamp(3rem,7vw,6.5rem)] uppercase leading-none text-black">{{ $article->title }}</h1>
                    @if ($article->excerpt)
                        <p class="mt-6 text-lg leading-8 text-slate-600">{{ $article->excerpt }}</p>
                    @endif
                </div>

                <div class="mx-auto max-w-6xl px-4 sm:px-6">
                    <img class="aspect-[4/3] w-full rounded-2xl object-cover sm:aspect-[16/8]" src="{{ $imageUrl }}" alt="{{ $article->title }}" loading="lazy">
                </div>

                <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 md:py-16">
                    <div class="space-y-5 text-base leading-8 text-slate-700 md:text-lg">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            </article>

            @if ($relatedArticles->isNotEmpty())
                <section class="bg-slate-50">
                    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 md:py-16">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[color:var(--brand-red)]">Artikel</p>
                                <h2 class="mt-3 font-display text-[clamp(2.6rem,5vw,4.5rem)] uppercase leading-none text-black">Artikel Lainnya</h2>
                            </div>
                            <a href="{{ route('articles.index') }}" class="article-more-link">Semua artikel</a>
                        </div>

                        <div class="mt-8 grid gap-6 md:grid-cols-3">
                            @foreach ($relatedArticles as $relatedArticle)
                                @php
                                    $relatedImage = $relatedArticle->image_url ?: 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?auto=format&fit=crop&w=700&q=80';
                                @endphp
                                <article class="overflow-hidden rounded-2xl border border-red-100/70 bg-white">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100">
                                        <img class="h-full w-full object-cover transition duration-300 hover:scale-105" src="{{ $relatedImage }}" alt="{{ $relatedArticle->title }}" loading="lazy">
                                    </a>
                                    <div class="p-5">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[color:var(--brand-red)]">{{ $relatedArticle->category }}</p>
                                        <h3 class="mt-3 break-words text-lg font-bold leading-snug text-slate-950">
                                            <a href="{{ route('articles.show', $relatedArticle) }}" class="hover:text-[color:var(--brand-red)]">{{ $relatedArticle->title }}</a>
                                        </h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </main>

        @include('partials.footer')
    </div>
@endsection
