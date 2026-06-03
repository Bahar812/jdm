<!DOCTYPE html>
<html lang="id">
    <head>
        @php
            $defaultTitle = 'CV. Juragan Daging Morowali | Supplier Frozen Food';
            $defaultDescription = 'CV. Juragan Daging Morowali menyediakan daging, ayam, ikan, seafood, dan frozen food berkualitas untuk kebutuhan usaha kuliner, retail, dan katering.';
            $seoTitle = trim($__env->yieldContent('seo_title', $defaultTitle));
            $seoDescription = trim($__env->yieldContent('seo_description', $defaultDescription));
            $seoUrl = trim($__env->yieldContent('seo_url', url()->current()));
            $seoImage = trim($__env->yieldContent('seo_image', asset('images/jdm-logo.png')));
            $seoType = trim($__env->yieldContent('seo_type', 'website'));
            $seoTwitterCard = trim($__env->yieldContent('seo_twitter_card', 'summary_large_image'));
            $seoRobots = trim($__env->yieldContent('seo_robots', 'index, follow, max-image-preview:large'));
            $seoJsonLd = trim($__env->yieldContent('seo_json_ld', ''));
            $businessSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                'name' => 'CV. Juragan Daging Morowali',
                'alternateName' => 'JDM Frozen Food',
                'url' => url('/'),
                'logo' => asset('images/jdm-logo.png'),
                'image' => asset('images/jdm-logo.png'),
                'description' => $defaultDescription,
                'email' => 'juragandagingmorowali@gmail.com',
                'telephone' => '+628552268888',
                'priceRange' => '$$',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Northwest Boulevard NV 15 No. 26, Citraland',
                    'addressLocality' => 'Surabaya',
                    'addressRegion' => 'Jawa Timur',
                    'postalCode' => '60196',
                    'addressCountry' => 'ID',
                ],
                'areaServed' => ['Morowali', 'Surabaya', 'Indonesia'],
                'sameAs' => [
                    'https://www.instagram.com/juragandagingmorowali/',
                ],
            ];
        @endphp
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $seoTitle }}</title>
        <meta name="description" content="{{ $seoDescription }}" />
        <meta name="robots" content="{{ $seoRobots }}" />
        <meta name="author" content="CV. Juragan Daging Morowali" />
        <link rel="canonical" href="{{ $seoUrl }}" />
        @include('partials.favicons')
        <meta property="og:locale" content="id_ID" />
        <meta property="og:site_name" content="CV. Juragan Daging Morowali" />
        <meta property="og:type" content="{{ $seoType }}" />
        <meta property="og:title" content="{{ $seoTitle }}" />
        <meta property="og:description" content="{{ $seoDescription }}" />
        <meta property="og:url" content="{{ $seoUrl }}" />
        <meta property="og:image" content="{{ $seoImage }}" />
        <meta property="og:image:alt" content="{{ $seoTitle }}" />
        <meta name="twitter:card" content="{{ $seoTwitterCard }}" />
        <meta name="twitter:title" content="{{ $seoTitle }}" />
        <meta name="twitter:description" content="{{ $seoDescription }}" />
        <meta name="twitter:image" content="{{ $seoImage }}" />
        <script type="application/ld+json">
            {!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
        @if ($seoJsonLd !== '')
            <script type="application/ld+json">{!! $seoJsonLd !!}</script>
        @endif
        @yield('seo_head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased">
        @yield('content')

        <a
            class="floating-whatsapp"
            href="https://wa.me/628552268888?text=Halo%20JDM%20Frozen%20Food%2C%20saya%20ingin%20bertanya%20tentang%20produk."
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Hubungi kami via WhatsApp"
        >
            <svg aria-hidden="true" viewBox="0 0 24 24" class="h-7 w-7" fill="currentColor">
                <path d="M12.04 2C6.58 2 2.14 6.43 2.14 11.87c0 1.74.46 3.44 1.33 4.94L2.05 22l5.32-1.39a9.9 9.9 0 0 0 4.67 1.18h.01c5.46 0 9.9-4.43 9.9-9.87C21.95 6.44 17.5 2 12.04 2Zm.01 18.1h-.01a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.15.82.84-3.07-.2-.32a8.1 8.1 0 0 1-1.25-4.34c0-4.51 3.69-8.18 8.24-8.18 2.2 0 4.27.85 5.83 2.4a8.12 8.12 0 0 1 2.42 5.78c0 4.51-3.7 8.23-8.24 8.23Zm4.52-6.15c-.25-.12-1.47-.72-1.7-.8-.23-.09-.4-.13-.56.12-.16.25-.64.8-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.38-2-1.22-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.23.25-.86.84-.86 2.05s.88 2.38 1 2.55c.12.17 1.73 2.64 4.19 3.7.59.25 1.04.4 1.4.52.59.19 1.12.16 1.54.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.29Z" />
            </svg>
        </a>
    </body>
</html>
