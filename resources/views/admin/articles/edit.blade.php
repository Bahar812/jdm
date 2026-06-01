@extends('layouts.admin')

@section('admin_kicker', 'Artikel')
@section('admin_title', 'Edit Artikel')

@section('admin_content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="{{ route('admin.articles.update', $article) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.articles._form', ['article' => $article])
            <div class="flex items-center gap-2">
                <button class="admin-btn-save px-4 py-2 text-[10px]" type="submit">Update</button>
                <a href="{{ route('admin.articles.index') }}" class="admin-btn-neutral px-4 py-2 text-[10px]">Batal</a>
            </div>
        </form>
    </div>
@endsection
