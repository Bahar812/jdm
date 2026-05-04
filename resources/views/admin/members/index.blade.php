@extends('layouts.admin')

@section('admin_kicker', 'Member')
@section('admin_title', 'CRUD Member')

@section('admin_content')
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <form method="GET" class="flex w-full max-w-md items-center gap-2">
            <input name="q" value="{{ $search }}" placeholder="Cari nama, email, no hp..." class="h-10 w-full rounded-xl border border-slate-200 px-4 text-sm">
            <button class="btn-outline px-4 py-2 text-[10px]" type="submit">Cari</button>
        </form>
        <a href="{{ route('admin.members.create') }}" class="btn-primary px-4 py-2 text-[10px]">Tambah Member</a>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No. HP</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $member->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->email }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->phone ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-600">{{ $member->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.members.edit', $member) }}" class="btn-outline px-3 py-2 text-[10px]">Edit</a>
                                <form method="POST" action="{{ route('admin.members.destroy', $member) }}" onsubmit="return confirm('Hapus member ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-outline px-3 py-2 text-[10px]" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Member belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $members->links() }}
    </div>
@endsection

