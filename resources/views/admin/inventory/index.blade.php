@extends('layouts.admin')

@section('admin_kicker', 'Inventory')
@section('admin_title', 'Manajemen Stok')

@section('admin_content')
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-lg font-bold text-slate-900">Adjust Stok</h2>
            <form method="POST" action="{{ route('admin.inventory.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="product_id">Produk</label>
                    <select id="product_id" name="product_id" class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm" required>
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((string) old('product_id') === (string) $product->id)>
                                {{ $product->name }} (stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="type">Tipe</label>
                    <select id="type" name="type" class="h-11 w-full rounded-xl border border-slate-200 px-3 text-sm" required>
                        <option value="in" @selected(old('type') === 'in')>Stock In</option>
                        <option value="out" @selected(old('type') === 'out')>Stock Out</option>
                        <option value="adjustment" @selected(old('type') === 'adjustment')>Set Manual</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="quantity">Quantity</label>
                    <input id="quantity" type="number" min="1" name="quantity" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('quantity', 1) }}" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="note">Catatan</label>
                    <textarea id="note" name="note" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('note') }}</textarea>
                </div>
                <button class="admin-btn-save w-full justify-center" type="submit">Simpan Perubahan</button>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h2 class="text-lg font-bold text-slate-900">Riwayat Inventory</h2>
                <form method="GET" class="flex items-center gap-2">
                    <select name="product_id" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((string) $selectedProductId === (string) $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <button class="admin-btn-neutral px-4 py-2 text-[10px]" type="submit">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-3 py-3">Tanggal</th>
                            <th class="px-3 py-3">Produk</th>
                            <th class="px-3 py-3">Tipe</th>
                            <th class="px-3 py-3">Qty</th>
                            <th class="px-3 py-3">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $move)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 text-slate-600">{{ $move->created_at->format('d/m H:i') }}</td>
                                <td class="px-3 py-3 text-slate-800">{{ $move->product?->name ?? '-' }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">{{ $move->type }}</span>
                                </td>
                                <td class="px-3 py-3 font-semibold text-slate-900">{{ $move->quantity }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $move->previous_stock }} -> {{ $move->new_stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-8 text-center text-slate-500">Belum ada pergerakan stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $movements->links() }}
            </div>
        </section>
    </div>
@endsection
