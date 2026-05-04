@php
    $isEdit = $member->exists;
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="name">Nama</label>
        <input id="name" name="name" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('name', $member->name) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="email">Email</label>
        <input id="email" type="email" name="email" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('email', $member->email) }}" required>
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="phone">No. HP</label>
        <input id="phone" name="phone" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" value="{{ old('phone', $member->phone) }}">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="role">Role</label>
        <select id="role" name="role" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" required>
            @php $role = old('role', $member->role ?: 'customer'); @endphp
            <option value="customer" @selected($role === 'customer')>Customer</option>
            <option value="admin" @selected($role === 'admin')>Admin</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="address">Alamat</label>
        <textarea id="address" name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('address', $member->address) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="password">
            Password {{ $isEdit ? '(isi jika ingin ganti)' : '' }}
        </label>
        <input id="password" type="password" name="password" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm" {{ $isEdit ? '' : 'required' }}>
    </div>
</div>

