<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Dashboard | Juragan Daging</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[linear-gradient(145deg,#d9deea_0%,#eef1f7_45%,#dbe2f2_100%)] text-slate-900 antialiased">
        <div class="mx-auto grid min-h-screen max-w-[1500px] grid-cols-1 gap-4 p-4 lg:grid-cols-[250px_1fr]">
            <aside class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">DM</span>
                    <div>
                        <p class="font-display text-2xl uppercase leading-none text-slate-900">Juragan Daging</p>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-500">Admin Panel</p>
                    </div>
                </a>

                <nav class="mt-7 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Member
                    </a>
                    <a href="{{ route('admin.sales-clients.index') }}" class="{{ request()->routeIs('admin.sales-clients.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Client Sales
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Produk
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Artikel
                    </a>
                    <a href="{{ route('admin.home-content.edit') }}" class="{{ request()->routeIs('admin.home-content.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        CMS Home
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Order Masuk
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="{{ request()->routeIs('admin.inventory.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} flex items-center rounded-xl px-4 py-3 text-sm font-semibold">
                        Inventory
                    </a>
                </nav>

                <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
                    <p class="font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="mt-1 uppercase tracking-[0.18em]">{{ auth()->user()->role }}</p>
                </div>
            </aside>

            <main class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-sm md:p-7">
                <header class="mb-6 flex flex-col gap-3 border-b border-slate-200 pb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">@yield('admin_kicker', 'Dashboard')</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900 md:text-3xl">@yield('admin_title', 'Admin Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="admin-btn-neutral px-4 py-2 text-[10px]">Lihat Website</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-[0.15em] text-white hover:bg-slate-700" type="submit">
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @yield('admin_content')
            </main>
        </div>
    </body>
</html>
