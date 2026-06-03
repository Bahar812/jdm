<footer class="border-t border-red-100/70 bg-white">
    @php
        $footerName = $homeCms['footer_business_name']
            ?? ($homeCmsDefaults['footer_business_name'] ?? 'CV. Juragan Daging Morowali');
    @endphp

    <div class="mx-auto flex max-w-6xl flex-col items-center justify-center gap-3 px-4 py-6 text-center text-xs text-slate-500 sm:px-6 md:flex-row md:justify-start md:text-left">
        <img class="h-16 w-16 object-contain" src="{{ asset('images/jdm-logo.png') }}" alt="Logo Juragan Daging Morowali">
        <p class="footer-business-name font-semibold uppercase">{{ $footerName }}</p>
    </div>
</footer>
