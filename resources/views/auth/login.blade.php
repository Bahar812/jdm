@extends('layouts.app')

@section('seo_title', 'Login Akun | JDM Frozen Food')
@section('seo_description', 'Halaman login akun JDM Frozen Food.')
@section('seo_robots', 'noindex, nofollow')

@section('content')
    <div class="min-h-screen bg-[linear-gradient(145deg,#e5e7eb_0%,#f8fafc_45%,#dbe1ef_100%)]">
        <x-navbar active-page="home" />

        <main class="mx-auto flex max-w-7xl items-center justify-center px-4 py-10 sm:px-6 md:py-16">
            <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_40px_90px_-55px_rgba(15,23,42,0.45)] lg:grid-cols-[1fr_1fr]">
                <section class="hidden bg-[linear-gradient(160deg,#1f2937_0%,#111827_100%)] p-10 text-white lg:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-300">Juragan Daging</p>
                    <h1 class="mt-4 font-display text-6xl uppercase leading-[0.95]">Akun JDM</h1>
                    <p class="mt-4 text-sm leading-relaxed text-slate-200">
                        Masuk untuk belanja produk frozen food, menyimpan data kontak, dan melanjutkan proses order.
                    </p>
                    <div class="mt-8 grid gap-3 text-sm">
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Belanja Produk</div>
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Keranjang & Checkout</div>
                        <div class="rounded-xl border border-white/20 bg-white/10 px-4 py-3">Data Kontak Tersimpan</div>
                    </div>
                </section>

                <section class="p-5 sm:p-8 md:p-10">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 sm:tracking-[0.3em]">Login</p>
                    <h2 class="mt-3 break-words font-display text-5xl uppercase text-slate-900">Masuk Akun</h2>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="email">Email</label>
                            <input id="email" type="email" name="email" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500" for="password">Password</label>
                            <input id="password" type="password" name="password" class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm focus:border-slate-400 focus:outline-none" required>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                            Ingat saya
                        </label>
                        <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-700" type="submit">
                            Masuk
                        </button>
                    </form>

                    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Belum punya akun?
                        <a class="font-semibold text-[color:var(--brand-red)] hover:underline" href="{{ route('register') }}">Daftar sebagai pengunjung</a>
                    </div>
                </section>
            </div>
        </main>
    </div>
@endsection
