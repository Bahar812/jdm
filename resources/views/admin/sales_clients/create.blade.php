@extends('layouts.admin')

@section('admin_kicker', 'Sales')
@section('admin_title', 'Tambah Client')

@section('admin_content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="{{ route('admin.sales-clients.store') }}" class="space-y-5">
            @csrf
            @include('admin.sales_clients._form', ['salesClient' => $salesClient])
            <div class="flex items-center gap-2">
                <button class="admin-btn-save px-4 py-2 text-[10px]" type="submit">Simpan</button>
                <a href="{{ route('admin.sales-clients.index') }}" class="admin-btn-neutral px-4 py-2 text-[10px]">Batal</a>
            </div>
        </form>
    </div>
@endsection
