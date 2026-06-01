@extends('layouts.admin')

@section('admin_kicker', 'Sales')
@section('admin_title', 'Edit Client')

@section('admin_content')
    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <form method="POST" action="{{ route('admin.sales-clients.update', $salesClient) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('admin.sales_clients._form', ['salesClient' => $salesClient])
                <div class="flex items-center gap-2">
                    <button class="admin-btn-save px-4 py-2 text-[10px]" type="submit">Update</button>
                    <a href="{{ route('admin.sales-clients.index') }}" class="admin-btn-neutral px-4 py-2 text-[10px]">Batal</a>
                </div>
            </form>
        </div>

        <aside class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Aktivitas</h2>
            <div class="mt-4 space-y-3">
                @forelse ($salesClient->activities as $activity)
                    <div class="rounded-xl border border-slate-100 px-3 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $activity->activity_date?->format('d M Y') }}</p>
                            <span class="rounded-full {{ \App\Models\SalesClient::statusBadgeClass($activity->status) }} px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]">
                                {{ \App\Models\SalesClient::statusLabel($activity->status) }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $activity->description ?: '-' }}</p>
                        @if ($activity->next_follow_up_at)
                            <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">Follow Up {{ $activity->next_follow_up_at->format('d M Y') }}</p>
                        @endif
                        @if ($activity->user)
                            <p class="mt-2 text-xs text-slate-500">{{ $activity->user->name }}</p>
                        @endif
                    </div>
                @empty
                    <p class="rounded-xl bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">Belum ada aktivitas sales.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
