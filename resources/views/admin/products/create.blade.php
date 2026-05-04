@extends('layouts.admin')

@section('admin_kicker', 'Produk')
@section('admin_title', 'Tambah Produk')

@section('admin_content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-5">
            @csrf
            @include('admin.products._form', ['product' => $product])
            <div class="flex items-center gap-2">
                <button class="btn-primary px-4 py-2 text-[10px]" type="submit">Simpan</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline px-4 py-2 text-[10px]">Batal</a>
            </div>
        </form>
    </div>
@endsection

