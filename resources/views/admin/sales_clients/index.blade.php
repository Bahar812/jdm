@extends('layouts.admin')

@section('admin_kicker', 'Sales')
@section('admin_title', 'Manajemen Client')

@section('admin_content')
    <div class="mb-4 space-y-3">
        <form method="GET" class="grid w-full gap-2 md:grid-cols-[1fr_170px_170px_auto]">
            <input name="q" value="{{ $search }}" placeholder="Cari resto, cafe, kontak, no hp..." class="h-10 rounded-xl border border-slate-200 px-4 text-sm">
            <select name="type" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
                <option value="">Semua Jenis</option>
                @foreach ($businessTypeOptions as $item)
                    <option value="{{ $item }}" @selected($businessType === $item)>{{ \App\Models\SalesClient::businessTypeLabel($item) }}</option>
                @endforeach
            </select>
            <select name="status" class="h-10 rounded-xl border border-slate-200 px-3 text-sm">
                <option value="">Semua Status</option>
                @foreach ($statusOptions as $item)
                    <option value="{{ $item }}" @selected($status === $item)>{{ \App\Models\SalesClient::statusLabel($item) }}</option>
                @endforeach
            </select>
            <button class="admin-btn-neutral px-4 py-2 text-[10px]" type="submit">Filter</button>
        </form>
        <div class="flex justify-end">
            <a href="{{ route('admin.sales-clients.create') }}" class="admin-btn-save px-4 py-2 text-[10px]">Tambah Client</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.14em] text-slate-500">
                    <th class="px-4 py-3">Client</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Kontak</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Dihubungi</th>
                    <th class="px-4 py-3">Follow Up</th>
                    <th class="px-4 py-3">Aktivitas</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salesClients as $client)
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $client->business_name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $client->contact_person ?: '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ \App\Models\SalesClient::businessTypeLabel($client->business_type) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $client->phone ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full {{ \App\Models\SalesClient::statusBadgeClass($client->status) }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]">
                                {{ \App\Models\SalesClient::statusLabel($client->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $client->last_contacted_at?->format('d M Y') ?: '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $client->next_follow_up_at?->format('d M Y') ?: '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <p>{{ \Illuminate\Support\Str::limit($client->latestActivity?->description ?: '-', 54) }}</p>
                            @if ($client->latestActivity)
                                <p class="mt-1 text-[10px] uppercase tracking-[0.12em] text-slate-400">{{ $client->latestActivity->activity_date?->format('d M Y') }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.sales-clients.edit', $client) }}" class="admin-btn-edit px-3 py-2 text-[10px]">Edit</a>
                                <form method="POST" action="{{ route('admin.sales-clients.destroy', $client) }}" onsubmit="return confirm('Hapus client sales ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-delete px-3 py-2 text-[10px]" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-slate-500">Data client sales belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $salesClients->links() }}
    </div>
@endsection
