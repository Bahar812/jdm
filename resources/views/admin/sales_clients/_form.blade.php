@php
    $lastContactedAt = old('last_contacted_at', $salesClient->last_contacted_at ? $salesClient->last_contacted_at->format('Y-m-d') : null);
    $nextFollowUpAt = old('next_follow_up_at', $salesClient->next_follow_up_at ? $salesClient->next_follow_up_at->format('Y-m-d') : null);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="business_name">Nama Resto/Cafe</label>
        <input id="business_name" name="business_name" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('business_name', $salesClient->business_name) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="business_type">Jenis Client</label>
        @php $selectedType = old('business_type', $salesClient->business_type ?: \App\Models\SalesClient::TYPE_RESTAURANT); @endphp
        <select id="business_type" name="business_type" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" required>
            @foreach ($businessTypeOptions as $item)
                <option value="{{ $item }}" @selected($selectedType === $item)>{{ \App\Models\SalesClient::businessTypeLabel($item) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="contact_person">PIC / Kontak</label>
        <input id="contact_person" name="contact_person" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('contact_person', $salesClient->contact_person) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="phone">No. HP</label>
        <input id="phone" name="phone" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('phone', $salesClient->phone) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="email">Email</label>
        <input id="email" type="email" name="email" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('email', $salesClient->email) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="status">Status Client</label>
        @php $selectedStatus = old('status', $salesClient->status ?: \App\Models\SalesClient::STATUS_PROSPECT); @endphp
        <select id="status" name="status" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" required>
            @foreach ($statusOptions as $item)
                <option value="{{ $item }}" @selected($selectedStatus === $item)>{{ \App\Models\SalesClient::statusLabel($item) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="last_contacted_at">Tanggal Dihubungi</label>
        <input id="last_contacted_at" type="date" name="last_contacted_at" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ $lastContactedAt }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="next_follow_up_at">Jadwal Follow Up</label>
        <input id="next_follow_up_at" type="date" name="next_follow_up_at" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ $nextFollowUpAt }}">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="address">Alamat</label>
        <textarea id="address" name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('address', $salesClient->address) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="cooperation_offer">Data Kerja Sama / Penawaran</label>
        <textarea id="cooperation_offer" name="cooperation_offer" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('cooperation_offer', $salesClient->cooperation_offer) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="notes">Catatan Client</label>
        <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('notes', $salesClient->notes) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="activity_notes">Catatan Aktivitas Sales</label>
        <textarea id="activity_notes" name="activity_notes" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('activity_notes') }}</textarea>
    </div>
</div>
