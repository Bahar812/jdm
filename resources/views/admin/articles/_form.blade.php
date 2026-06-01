<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="title">Judul Artikel</label>
        <input id="title" name="title" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('title', $article->title) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="slug">Slug</label>
        <input id="slug" name="slug" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('slug', $article->slug) }}" placeholder="otomatis dari judul">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="category">Kategori</label>
        <input id="category" name="category" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('category', $article->category ?: 'Artikel') }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="published_at">Tanggal Publish</label>
        <input id="published_at" type="datetime-local" name="published_at" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="image_url">Image URL</label>
        <input id="image_url" name="image_url" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('image_url', $article->image_url) }}" placeholder="https://...">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="excerpt">Ringkasan</label>
        <textarea id="excerpt" name="excerpt" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm" maxlength="500">{{ old('excerpt', $article->excerpt) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="content">Konten</label>
        <textarea id="content" name="content" rows="12" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6" required>{{ old('content', $article->content) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published ?? false))>
            Publish artikel di website
        </label>
    </div>
</div>
