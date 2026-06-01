<footer class="border-t border-red-100/70 bg-white">
    @php
        $footerName = $homeCms['footer_business_name']
            ?? ($homeCmsDefaults['footer_business_name'] ?? 'CV. Juragan Daging Morowali');
    @endphp

    <div class="mx-auto flex max-w-6xl justify-center px-4 py-6 text-center text-xs text-slate-500 sm:px-6 md:justify-start md:text-left">
        <p class="footer-business-name font-semibold uppercase">{{ $footerName }}</p>
    </div>
</footer>
