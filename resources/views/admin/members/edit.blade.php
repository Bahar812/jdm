@extends('layouts.admin')

@section('admin_kicker', 'Member')
@section('admin_title', 'Edit Member')

@section('admin_content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <form method="POST" action="{{ route('admin.members.update', $member) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.members._form', ['member' => $member])
            <div class="flex items-center gap-2">
                <button class="btn-primary px-4 py-2 text-[10px]" type="submit">Update</button>
                <a href="{{ route('admin.members.index') }}" class="btn-outline px-4 py-2 text-[10px]">Batal</a>
            </div>
        </form>
    </div>
@endsection

