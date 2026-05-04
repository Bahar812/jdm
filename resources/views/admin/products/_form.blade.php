<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="name">Nama Produk</label>
        <input id="name" name="name" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('name', $product->name) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="slug">Slug</label>
        <input id="slug" name="slug" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('slug', $product->slug) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="category">Kategori</label>
        <input id="category" name="category" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('category', $product->category) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="badge">Badge</label>
        <input id="badge" name="badge" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('badge', $product->badge) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="unit">Unit</label>
        <input id="unit" name="unit" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('unit', $product->unit ?: 'kg') }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="price">Harga</label>
        <input id="price" type="number" min="0" name="price" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('price', $product->price) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="stock">Stok</label>
        <input id="stock" type="number" min="0" name="stock" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="image_url">Image URL</label>
        <input id="image_url" name="image_url" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('image_url', $product->image_url) }}">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="description">Deskripsi</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            Produk aktif
        </label>
    </div>
</div>

