@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden bg-[color:var(--paper)]">
        <div class="pointer-events-none absolute inset-0 bg-grid opacity-[0.35]"></div>
        <div class="pointer-events-none absolute -left-32 top-16 h-72 w-72 rounded-full bg-[color:var(--brand-red-10)] blur-3xl"></div>
        <div class="pointer-events-none absolute right-[-10%] top-[-20%] h-[28rem] w-[28rem] rounded-full bg-[color:var(--brand-red-15)] blur-3xl"></div>

        <x-navbar active-page="home" />

        <main>
            <section id="hero" class="relative">
                <div class="mx-auto grid max-w-6xl items-center gap-14 px-6 pb-16 pt-14 lg:grid-cols-[1.05fr_0.95fr] lg:pt-24">
                    <div class="animate-fade-up">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <span class="h-3 w-3 rounded-full bg-[color:var(--brand-red)]"></span>
                                <span class="h-3 w-6 rounded-full bg-[color:var(--brand-red-20)]"></span>
                            </span>
                            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-slate-500">
                                General Supplier Frozen Food
                            </span>
                        </div>
                        <h1 class="mt-6 font-display text-4xl uppercase leading-[0.98] text-[color:var(--brand-ink)] sm:text-5xl md:text-7xl">
                            CV. Juragan Daging Morowali
                        </h1>
                        <p class="mt-6 text-lg leading-relaxed text-slate-700">
                            CV. Juragan Daging Morowali merupakan perusahaan yang bergerak di bidang General Supplier. Kami
                            melayani kebutuhan Frozen Food seperti daging, ayam, dan ikan dengan kualitas terbaik.
                        </p>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">
                            Halal - Bermutu - Praktis
                        </p>
                        <div class="mt-8 flex flex-wrap items-center gap-5">
                            <a class="btn-primary" href="{{ route('contact') }}">Hubungi Kami</a>
                            <a class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)] transition hover:text-[color:var(--brand-red-dark)]" href="{{ route('products') }}">
                                Lihat Produk <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </div>
                    <div class="relative mt-10 animate-fade-up-delay lg:mt-0">
                        <div class="pointer-events-none absolute -top-8 left-1/2 h-16 w-28 -translate-x-1/2 opacity-70 bg-[radial-gradient(circle,rgba(181,18,27,0.25)_2px,transparent_2px)] [background-size:12px_12px]"></div>
                        <div class="pointer-events-none absolute -right-8 top-6 h-20 w-20 rounded-3xl bg-[color:var(--brand-red-90)] shadow-[0_20px_50px_-20px_rgba(181,18,27,0.7)] animate-float"></div>
                        <div class="pointer-events-none absolute -left-10 bottom-6 h-14 w-14 rounded-full border-4 border-[color:var(--brand-red-40)]"></div>

                        <div class="relative mx-auto grid w-full max-w-[34rem] grid-cols-2 gap-6 lg:gap-8">
                            <div class="flex flex-col gap-6">
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_30px_70px_-45px_rgba(125,12,20,0.45)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-56 w-full object-cover sm:h-64" src="https://images.unsplash.com/photo-1603048297172-c92544798d5a?auto=format&fit=crop&w=1200&q=80" alt="Frozen beef premium">
                                        <span class="absolute left-4 top-4 rounded-full border border-white/70 bg-white/85 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                            Live Call
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_28px_60px_-45px_rgba(125,12,20,0.4)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-44 w-full object-cover sm:h-52" src="https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1200&q=80" alt="Frozen chicken fillet">
                                        <span class="absolute bottom-4 right-4 rounded-full bg-[color:var(--brand-red)] px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-white">
                                            Ask Me
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_28px_60px_-45px_rgba(125,12,20,0.4)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-40 w-full object-cover sm:h-48" src="https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?auto=format&fit=crop&w=1200&q=80" alt="Frozen fish fillet">
                                        <span class="absolute left-4 bottom-4 rounded-full border border-white/70 bg-white/85 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                            Support
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col-reverse gap-6 pt-8 sm:pt-12">
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_30px_70px_-45px_rgba(125,12,20,0.45)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-56 w-full object-cover sm:h-64" src="https://images.unsplash.com/photo-1565680018434-b513d7f756f0?auto=format&fit=crop&w=1200&q=80" alt="Seafood supplier">
                                        <span class="absolute right-4 top-4 rounded-full bg-white/90 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                            Always On
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_28px_60px_-45px_rgba(125,12,20,0.4)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-44 w-full object-cover sm:h-52" src="https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&w=1200&q=80" alt="Daging sapi beku">
                                        <span class="absolute bottom-4 left-4 rounded-full border border-white/70 bg-white/85 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                            Fast Help
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-[2.4rem] border border-red-100/70 bg-white p-2 shadow-[0_28px_60px_-45px_rgba(125,12,20,0.4)]">
                                    <div class="relative overflow-hidden rounded-[2rem]">
                                        <img class="h-40 w-full object-cover sm:h-48" src="https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=1200&q=80" alt="Produk olahan frozen food">
                                        <span class="absolute left-4 top-4 rounded-full border border-white/70 bg-white/85 px-4 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-700">
                                            Quick Reply
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="profile" class="relative">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                                Company Profile
                            </p>
                            <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em] text-[color:var(--brand-ink)]">
                                Tentang Perusahaan
                            </h2>
                            <p class="mt-5 text-base leading-relaxed text-slate-700">
                                CV. Juragan Daging Morowali adalah perusahaan General Supplier yang fokus menyediakan frozen
                                food untuk wilayah Morowali dan sekitarnya. Kami menjaga proses seleksi produk, penyimpanan,
                                dan distribusi agar kualitas tetap prima hingga ke tangan mitra.
                            </p>
                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="card">
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                        Bahasa Indonesia
                                    </p>
                                    <p class="mt-3 text-sm text-slate-600">
                                        Melayani kebutuhan Frozen Food seperti daging, ayam, dan ikan dengan kualitas terbaik.
                                    </p>
                                </div>
                                <div class="card">
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                        English
                                    </p>
                                    <p class="mt-3 text-sm text-slate-600">
                                        We serve frozen food needs such as meat, chicken, and fish with the best quality.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Keunggulan</p>
                                <h3 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">
                                    Kualitas Terjaga
                                </h3>
                                <p class="mt-3 text-sm text-slate-600">
                                    Mengutamakan standar kualitas dan kebersihan untuk setiap kategori produk.
                                </p>
                            </div>
                            <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Fokus</p>
                                <h3 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Mitra Usaha</h3>
                                <p class="mt-3 text-sm text-slate-600">
                                    Menyediakan kebutuhan mitra usaha dengan harga pantas dan pelayanan yang ramah.
                                </p>
                            </div>
                            <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Rantai Dingin</p>
                                <h3 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Stabil</h3>
                                <p class="mt-3 text-sm text-slate-600">
                                    Menjaga suhu penyimpanan agar kualitas tetap segar dan praktis diolah.
                                </p>
                            </div>
                            <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_35px_70px_-45px_rgba(125,12,20,0.55)]">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Layanan</p>
                                <h3 class="mt-3 font-display text-3xl uppercase text-[color:var(--brand-red)]">Responsif</h3>
                                <p class="mt-3 text-sm text-slate-600">
                                    Komunikasi cepat untuk memastikan kebutuhan bisnis terpenuhi tanpa hambatan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="produk" class="relative">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                                Produk & Layanan
                            </p>
                            <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em] text-[color:var(--brand-ink)]">
                                Frozen Food Berkualitas
                            </h2>
                        </div>
                        <p class="max-w-xl text-sm text-slate-600">
                            Ragam produk dipilih untuk memenuhi kebutuhan usaha kuliner, retail, hingga katering dengan
                            standar mutu terbaik.
                        </p>
                    </div>
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        <div class="rounded-3xl border border-red-100/70 bg-white p-6 shadow-[0_30px_70px_-45px_rgba(125,12,20,0.5)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Kategori</p>
                            <h3 class="mt-4 font-display text-3xl uppercase text-[color:var(--brand-red)]">Daging</h3>
                            <p class="mt-3 text-sm text-slate-600">
                                Pilihan daging beku dengan kualitas serat dan warna yang terjaga.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-red-100/70 bg-white p-6 shadow-[0_30px_70px_-45px_rgba(125,12,20,0.5)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Kategori</p>
                            <h3 class="mt-4 font-display text-3xl uppercase text-[color:var(--brand-red)]">Ayam</h3>
                            <p class="mt-3 text-sm text-slate-600">
                                Produk ayam beku siap olah untuk menu harian maupun skala besar.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-red-100/70 bg-white p-6 shadow-[0_30px_70px_-45px_rgba(125,12,20,0.5)]">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Kategori</p>
                            <h3 class="mt-4 font-display text-3xl uppercase text-[color:var(--brand-red)]">Ikan</h3>
                            <p class="mt-3 text-sm text-slate-600">
                                Varian ikan segar beku, praktis dan menjaga rasa alami.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="visi-misi" class="relative bg-[color:var(--brand-red)] text-white">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr]">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-white/80">Visi & Misi</p>
                            <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em]">Vision & Mission</h2>
                            <p class="mt-4 text-sm text-white/80">
                                Komitmen kami untuk menjadi penyedia makanan halal, bermutu, dan praktis bagi seluruh mitra.
                            </p>
                        </div>
                        <div class="grid gap-6">
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-6">
                                <h3 class="font-display text-3xl uppercase">Visi</h3>
                                <div class="mt-4 space-y-4 text-sm text-white/85">
                                    <p>
                                        Menjadikan Perusahaan Penyedia Makanan yang terjamin kehalalannya, bermutu serta
                                        praktis. Menjadi General Supplier yang terdepan dan memberikan yang terbaik bagi
                                        pelanggan.
                                    </p>
                                    <p>成为一家保证清真、品质和实用的食品供应公司。成为领先的总供应商,为客户提供最好的服务。</p>
                                    <p>
                                        To become a food supply company that is guaranteed to be halal, quality and practical.
                                        Become a leading General Supplier and provide the best for customers.
                                    </p>
                                </div>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/10 p-6">
                                <h3 class="font-display text-3xl uppercase">Misi</h3>
                                <div class="mt-4 space-y-4 text-sm text-white/85">
                                    <p>
                                        Mengembangkan dan mengenalkan produk makanan lokal yang bermutu. Menyediakan seluruh
                                        kebutuhan bagi mitra usaha dengan harga yang pantas dengan kualitas yang terbaik.
                                    </p>
                                    <p>开发和引进本地优质食品。以合理的价格以最好的质量提供商业伙伴的所有需求。</p>
                                    <p>
                                        Develop and introduce quality local food products. Providing all the needs for business
                                        partners at reasonable prices with the best quality.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="scope" class="relative">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                                Ruang Lingkup Pekerjaan
                            </p>
                            <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em] text-[color:var(--brand-ink)]">
                                Scope of Work
                            </h2>
                        </div>
                        <p class="max-w-xl text-sm text-slate-600">
                            Perusahaan kami menyediakan berbagai macam Frozen Food dengan kualitas terbaik untuk semua lini
                            kebutuhan usaha.
                        </p>
                    </div>
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        <div class="rounded-3xl border border-red-100/70 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Bahasa Indonesia</p>
                            <p class="mt-4 text-sm text-slate-600">
                                Perusahaan kami menyediakan berbagai macam Frozen Food dengan kualitas terbaik.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-red-100/70 bg-[color:var(--brand-red)] p-6 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">中文</p>
                            <p class="mt-4 text-sm text-white/85">我公司提供各种质量最好的冷冻食品。</p>
                        </div>
                        <div class="rounded-3xl border border-red-100/70 bg-white p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">English</p>
                            <p class="mt-4 text-sm text-slate-600">
                                Our company provides various kinds of frozen food with the best quality.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="relative">
                <div class="mx-auto max-w-6xl px-6 pb-20 pt-16">
                    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-[color:var(--brand-red)]">
                                Hubungi Kami
                            </p>
                            <h2 class="mt-4 font-display text-4xl uppercase tracking-[0.1em] text-[color:var(--brand-ink)]">
                                Siap Menjadi Mitra Anda
                            </h2>
                            <p class="mt-4 text-base text-slate-700">
                                Kami siap memenuhi kebutuhan frozen food Anda dengan kualitas terbaik dan layanan yang cepat.
                                Hubungi kami untuk informasi produk, harga, dan kemitraan.
                            </p>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <a class="btn-primary" href="mailto:juragandagingmorowali@gmail.com">Email Kami</a>
                                <a class="btn-outline" href="tel:+6281215054099">Telepon</a>
                                <a class="btn-outline" href="https://wa.me/6281215054099">WhatsApp</a>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-red-100/70 bg-white/90 p-6 shadow-[0_40px_70px_-45px_rgba(125,12,20,0.5)]">
                            <h3 class="font-display text-3xl uppercase text-[color:var(--brand-red)]">Kontak Utama</h3>
                            <div class="mt-5 space-y-4 text-sm text-slate-600">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Email</p>
                                    <p class="mt-1 text-base">juragandagingmorowali@gmail.com</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Telepon</p>
                                    <p class="mt-1 text-base">0812 1504 5099</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Alamat</p>
                                    <p class="mt-1 text-base">
                                        Desa Sakita, Kecamatan Bungku Tengah, Kabupaten Morowali, Provinsi Sulawesi Tengah
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 rounded-2xl bg-[color:var(--brand-red-10)] p-4 text-xs text-slate-600">
                                <p class="font-semibold uppercase tracking-[0.3em] text-[color:var(--brand-red)]">
                                    Working Area
                                </p>
                                <p class="mt-2">
                                    Morowali dan sekitarnya untuk suplai frozen food yang konsisten dan tepat waktu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        @include('partials.footer')
    </div>
@endsection

