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
    <div class="relative mx-auto flex w-full max-w-[1360px] items-center px-5 py-4 lg:px-4">
        <a class="flex items-center gap-3" href="{{ route('home') }}">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[color:var(--brand-red)] text-white">
                <span class="font-display text-xl tracking-[0.2em]">DM</span>
            </span>
            <div>
                <p class="font-display text-2xl uppercase tracking-[0.08em] text-[color:var(--brand-red)]">
                    Juragan Daging
                </p>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Morowali</p>
            </div>
        </a>

        <nav class="absolute left-1/2 hidden -translate-x-1/2 items-center gap-6 text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 md:flex">
            @foreach ($links as $link)
                <a
                    class="transition hover:text-[color:var(--brand-red)]"
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
    </div>
</header>



