@props(['activePage' => 'home'])

@php
    $links = [
        ['key' => 'home', 'label' => 'Beranda', 'route' => 'home'],
        ['key' => 'products', 'label' => 'Produk', 'route' => 'products'],
        ['key' => 'vision_mission', 'label' => 'Visi & Misi', 'route' => 'vision_mission'],
        ['key' => 'contact', 'label' => 'Kontak', 'route' => 'contact'],
    ];
    $cartRaw = session('shopping_cart', []);
    $cartItemCount = is_array($cartRaw) ? array_sum(array_map('intval', $cartRaw)) : 0;
@endphp

<header class="sticky top-0 z-50 border-b border-red-100/70 bg-white/90 text-slate-950 shadow-sm backdrop-blur">
    <div class="relative mx-auto flex w-full max-w-[1360px] items-center px-4 py-3 sm:px-5 md:py-4 lg:px-4" data-site-nav>
        <a class="flex min-w-0 items-center gap-2 sm:gap-3" href="{{ route('home') }}">
            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-red-100 bg-white p-0.5 shadow-sm sm:h-20 sm:w-20">
                <img class="h-full w-full object-contain" src="{{ asset('images/jdm-logo.png') }}" alt="Logo Juragan Daging Morowali">
            </span>
            <div class="min-w-0">
                <p class="truncate font-display text-xl uppercase tracking-[0.06em] text-[color:var(--brand-red)] sm:text-2xl sm:tracking-[0.08em]">
                    Juragan Daging
                </p>
                <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-slate-500 sm:text-xs sm:tracking-[0.35em]">Morowali</p>
            </div>
        </a>

        <nav class="absolute left-1/2 hidden -translate-x-1/2 items-center gap-6 text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 md:flex">
            @foreach ($links as $link)
                <a
                    class="{{ $activePage === $link['key'] ? 'text-[color:var(--brand-red)]' : '' }} transition hover:text-[color:var(--brand-red)]"
                    href="{{ route($link['route']) }}"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="ml-auto hidden items-center md:flex">
            @auth
                @if (auth()->user()->isAdmin())
                    <a class="order-1 btn-primary" href="{{ route('admin.dashboard') }}">Dashboard</a>
                @else
                    <a class="order-1 btn-primary" href="{{ route('products') }}">Akun Saya</a>
                @endif

                <form class="order-1 ml-2" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn-outline px-4 py-2 text-[10px]" type="submit">Logout</button>
                </form>
            @else
                <a class="order-1 btn-primary" href="{{ route('login') }}">Login</a>
            @endauth
            <a
                class="order-2 ml-3 inline-flex h-14 w-14 items-center justify-center text-black transition hover:text-[color:var(--brand-red)] relative"
                href="{{ route('cart') }}"
                aria-label="Keranjang"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.5 3h2l2.4 11.5a2 2 0 0 0 2 1.5h8.8a2 2 0 0 0 2-1.6L22 7H6.2"></path>
                </svg>
                @if ($cartItemCount > 0)
                    <span class="absolute bottom-0 right-0 z-10 inline-flex h-5 w-5 translate-x-1/2 translate-y-1/2 items-center justify-center rounded-full bg-[color:var(--brand-red)] text-[9px] font-bold leading-none text-white ring-2 ring-white">
                        {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                    </span>
                @endif
            </a>
        </div>

        <div class="ml-auto flex items-center gap-1 md:hidden">
            <a
                class="relative inline-flex h-11 w-11 items-center justify-center rounded-full text-black transition hover:bg-red-50 hover:text-[color:var(--brand-red)]"
                href="{{ route('cart') }}"
                aria-label="Keranjang"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.5 3h2l2.4 11.5a2 2 0 0 0 2 1.5h8.8a2 2 0 0 0 2-1.6L22 7H6.2"></path>
                </svg>
                @if ($cartItemCount > 0)
                    <span class="absolute bottom-1 right-1 z-10 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[color:var(--brand-red)] text-[9px] font-bold leading-none text-white ring-2 ring-white">
                        {{ $cartItemCount > 99 ? '99+' : $cartItemCount }}
                    </span>
                @endif
            </a>
            <button
                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-red-100 text-slate-900 transition hover:border-[color:var(--brand-red)] hover:text-[color:var(--brand-red)]"
                type="button"
                data-site-nav-toggle
                aria-expanded="false"
                aria-controls="mobile-site-nav"
                aria-label="Buka menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 6h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div
            id="mobile-site-nav"
            class="absolute left-4 right-4 top-[calc(100%+0.75rem)] hidden rounded-2xl border border-red-100 bg-white p-4 shadow-[0_24px_70px_-42px_rgba(125,12,20,0.55)] md:hidden"
            data-site-nav-menu
        >
            <nav class="grid gap-2 text-xs font-semibold uppercase tracking-[0.22em] text-slate-600" aria-label="Menu mobile">
                @foreach ($links as $link)
                    <a
                        class="{{ $activePage === $link['key'] ? 'bg-red-50 text-[color:var(--brand-red)]' : 'hover:bg-slate-50 hover:text-slate-950' }} rounded-xl px-4 py-3 transition"
                        href="{{ route($link['route']) }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-3 grid gap-2 border-t border-slate-100 pt-3">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a class="btn-primary justify-center px-4 py-3 text-[10px]" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    @else
                        <a class="btn-primary justify-center px-4 py-3 text-[10px]" href="{{ route('products') }}">Akun Saya</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-outline w-full justify-center px-4 py-3 text-[10px]" type="submit">Logout</button>
                    </form>
                @else
                    <a class="btn-primary justify-center px-4 py-3 text-[10px]" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>
</header>



