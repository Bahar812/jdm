@extends('layouts.app')

@section('seo_title', 'Daftar Akun | JDM Frozen Food')
@section('seo_description', 'Daftar akun pengunjung JDM Frozen Food.')
@section('seo_robots', 'noindex, nofollow')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(145deg,#e5e7eb_0%,#f8fafc_45%,#dbe1ef_100%)]">
        <x-navbar active-page="home" />

        <main class="mx-auto flex max-w-7xl items-center justify-center px-4 py-10 sm:px-6 md:py-16">
            <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_40px_90px_-55px_rgba(15,23,42,0.45)] lg:grid-cols-[0.9fr_1.1fr]">
                <section class="hidden bg-[linear-gradient(160deg,#1f2937_0%,#111827_100%)] p-10 text-white lg:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-300">Juragan Daging</p>
                    <h1 class="mt-4 font-display text-6xl uppercase leading-[0.95]">Customer Access</h1>
                    <p class="mt-4 text-sm leading-relaxed text-slate-200">
                        Buat akun untuk belanja frozen food, menyimpan data kontak, dan memudahkan proses order berikutnya.
                    </p>
                    <div class="mt-8 grid gap-3 text-sm">
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Akun Customer</div>
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Checkout Lebih Mudah</div>
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Riwayat Order Terhubung</div>
                    </div>
                </section>

                <section class="p-5 sm:p-8 md:p-10">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 sm:tracking-[0.3em]">Register</p>
                    <h2 class="mt-3 break-words font-display text-5xl uppercase text-slate-900">Daftar Akun</h2>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('register.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="name">Nama</label>
                                <input id="name" type="text" name="name" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="email">Email</label>
                                <input id="email" type="email" name="email" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="phone">No. HP</label>
                            <input id="phone" type="text" name="phone" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" value="{{ old('phone') }}">
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="address">Alamat</label>
                            <textarea id="address" name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-slate-400 focus:outline-none">{{ old('address') }}</textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="password">Password</label>
                                <input id="password" type="password" name="password" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="password_confirmation">Konfirmasi</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" required>
                            </div>
                        </div>

                        <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-700" type="submit">
                            Daftar
                        </button>
                    </form>

                    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Sudah punya akun?
                        <a class="font-semibold text-[color:var(--brand-red)] hover:underline" href="{{ route('login') }}">Masuk di sini</a>
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection
