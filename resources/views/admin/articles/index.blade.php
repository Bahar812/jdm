@extends('layouts.admin')

@section('admin_kicker', 'Artikel')
@section('admin_title', 'CRUD Artikel')

@section('admin_content')
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form method="GET" class="flex w-full max-w-md items-center gap-2">
            <input name="q" value="{{ $search }}" placeholder="Cari judul, kategori, slug..." class="h-10 w-full rounded-xl border border-slate-200 px-4 text-sm">
            <button class="admin-btn-neutral px-4 py-2 text-[10px]" type="submit">Cari</button>
        </form>
        <a href="{{ route('admin.articles.create') }}" class="admin-btn-save px-4 py-2 text-[10px]">Tambah Artikel</a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-3">Artikel</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Publish</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $article->title }}</p>
                            <p class="text-xs text-slate-500">{{ $article->slug }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $article->category }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $article->published_at?->format('d M Y H:i') ?: '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full {{ $article->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                {{ $article->is_published ? 'Publish' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($article->is_published)
                                    <a href="{{ route('articles.show', $article) }}" target="_blank" rel="noopener noreferrer" class="admin-btn-neutral px-3 py-2 text-[10px]">Lihat</a>
                                @endif
                                <a href="{{ route('admin.articles.edit', $article) }}" class="admin-btn-edit px-3 py-2 text-[10px]">Edit</a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-delete px-3 py-2 text-[10px]" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Artikel belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
@endsection
