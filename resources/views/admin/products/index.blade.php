@extends('layouts.admin')

@section('admin_kicker', 'Produk')
@section('admin_title', 'CRUD Produk')

@section('admin_content')
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form method="GET" class="flex w-full max-w-md items-center gap-2">
            <input name="q" value="{{ $search }}" placeholder="Cari nama, kategori, slug..." class="h-10 w-full rounded-xl border border-slate-200 px-4 text-sm">
            <button class="btn-outline px-4 py-2 text-[10px]" type="submit">Cari</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn-primary px-4 py-2 text-[10px]">Tambah Produk</a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Stok</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">{{ $product->slug }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->category }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">{{ $product->stock }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline px-3 py-2 text-[10px]">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-outline px-3 py-2 text-[10px]" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Produk belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection

