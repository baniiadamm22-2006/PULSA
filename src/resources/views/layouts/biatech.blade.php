<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0B102F">
    <meta name="description" content="BIATECH — pulsa, paket data, roaming internasional, paket Umroh, dan token listrik dalam satu website.">
    <title>@yield('title', 'BIATECH — Produk Digital Praktis')</title>
    <link rel="stylesheet" href="{{ asset('biatech/css/storefront.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('biatech/images/biatech-favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('biatech/css/brand-theme.css') }}">
    @livewireStyles
</head>
<body>
    <a class="skip-link" href="#main-content">Langsung ke konten</a>
    <div class="utility-bar">
        <div class="container utility-bar__inner">
            <span><x-biatech.icon name="sparkles" :size="14" /> Solusi praktis kebutuhan digitalmu</span>
            <a href="{{ route('biatech.help') }}"><x-biatech.icon name="headset" :size="14" /> Pusat bantuan</a>
        </div>
    </div>
    <header class="site-header">
        <div class="container site-header__inner">
            <a href="{{ route('biatech.home') }}" class="brand" aria-label="BIATECH beranda">
                <span class="brand__mark"><img src="{{ asset('biatech/images/biatech-logo.png') }}" alt="" width="44" height="44"></span>
                <span><strong>BIA<span>TECH</span></strong><small>Digital • Mudah • Cepat</small></span>
            </a>
            <form class="header-search" method="GET" action="{{ route('biatech.catalog') }}" role="search">
                <input type="search" name="q" placeholder="Cari pulsa, paket data, roaming, token listrik..." aria-label="Cari produk digital" value="{{ request('q', '') }}">
                <button aria-label="Cari produk" type="submit"><x-biatech.icon name="search" :size="20" /></button>
            </form>
            <nav class="header-actions" aria-label="Akses cepat">
                <a href="{{ route('biatech.catalog') }}" class="header-actions__item"><x-biatech.icon name="grid" :size="22" /><span>Lihat <small>Produk</small></span></a>
                <a href="{{ route('biatech.help') }}" class="header-actions__item"><x-biatech.icon name="headset" :size="23" /><span>Pusat <small>Bantuan</small></span></a>
                <a href="{{ route('biatech.catalog') }}" class="header-actions__cta"><x-biatech.icon name="grid" :size="20" /><span>Produk</span></a>
            </nav>
        </div>
        <div class="desktop-nav">
            <div class="container desktop-nav__inner">
                <a href="{{ route('biatech.home') }}" class="{{ request()->routeIs('biatech.home') ? 'is-active' : '' }}">Beranda</a>
                <a href="{{ route('biatech.catalog') }}" class="{{ request()->routeIs('biatech.catalog') && blank(request()->query('category')) ? 'is-active' : '' }}">Semua Produk</a>
                <a href="{{ route('biatech.catalog', ['category' => 'pulsa-reguler']) }}" class="{{ request()->routeIs('biatech.catalog') && request()->query('category') === 'pulsa-reguler' ? 'is-active' : '' }}">Pulsa</a>
                <a href="{{ route('biatech.catalog', ['category' => 'paket-data-internet']) }}" class="{{ request()->routeIs('biatech.catalog') && request()->query('category') === 'paket-data-internet' ? 'is-active' : '' }}">Paket Data</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-internasional']) }}" class="{{ request()->routeIs('biatech.catalog') && request()->query('category') === 'roaming-internasional' ? 'is-active' : '' }}">Roaming Internasional</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-umroh']) }}" class="{{ request()->routeIs('biatech.catalog') && request()->query('category') === 'roaming-umroh' ? 'is-active' : '' }}">Paket Umroh</a>
                <a href="{{ route('biatech.catalog', ['category' => 'token-listrik-pln']) }}" class="{{ request()->routeIs('biatech.catalog') && request()->query('category') === 'token-listrik-pln' ? 'is-active' : '' }}">Token PLN</a>
                <span class="desktop-nav__spacer"></span>
                <a class="desktop-nav__highlight" href="{{ route('biatech.help') }}"><x-biatech.icon name="arrow-right" :size="16" /> Bantuan</a>
            </div>
        </div>
        <details class="mobile-nav">
            <summary><x-biatech.icon name="menu" :size="20" /> Menu & kategori <x-biatech.icon name="chevron-down" :size="18" /></summary>
            <nav aria-label="Menu mobile">
                <a href="{{ route('biatech.home') }}">Beranda</a>
                <a href="{{ route('biatech.catalog') }}">Semua produk</a>
                <a href="{{ route('biatech.catalog', ['category' => 'pulsa-reguler']) }}">Pulsa</a>
                <a href="{{ route('biatech.catalog', ['category' => 'paket-data-internet']) }}">Paket Internet</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-internasional']) }}">Roaming Internasional</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-umroh']) }}">Roaming Umroh</a>
                <a href="{{ route('biatech.catalog', ['category' => 'token-listrik-pln']) }}">Token PLN</a>
                <a href="{{ route('biatech.help') }}">Pusat Bantuan</a>
            </nav>
        </details>
    </header>

    <main id="main-content">@yield('content')</main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-about">
                <a href="{{ route('biatech.home') }}" class="brand brand--footer"><span class="brand__mark"><img src="{{ asset('biatech/images/biatech-logo.png') }}" alt="" width="44" height="44"></span><span><strong>BIA<span>TECH</span></strong><small>Digital • Mudah • Cepat</small></span></a>
                <p>Jelajahi informasi pulsa, internet, roaming, dan listrik prabayar melalui katalog BIATECH.</p>
            </div>
            <div class="footer-links"><h3>Produk digital</h3>
                <a href="{{ route('biatech.catalog', ['category' => 'pulsa-reguler']) }}">Pulsa reguler</a>
                <a href="{{ route('biatech.catalog', ['category' => 'paket-data-internet']) }}">Paket internet</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-internasional']) }}">Roaming internasional</a>
                <a href="{{ route('biatech.catalog', ['category' => 'roaming-umroh']) }}">Roaming Umroh</a>
                <a href="{{ route('biatech.catalog', ['category' => 'token-listrik-pln']) }}">Token PLN</a>
            </div>
            <div class="footer-links"><h3>Layanan</h3>
                <a href="{{ route('biatech.home') }}">Beranda</a>
                <a href="{{ route('biatech.catalog') }}">Katalog produk</a>
                <a href="{{ route('biatech.catalog') }}">Kategori produk</a>
                <a href="{{ route('biatech.help') }}">Pertanyaan umum</a>
            </div>
            <div class="footer-contact"><h3>Butuh bantuan?</h3><p>Ada pertanyaan seputar layanan atau status pesanan? Lihat pusat bantuan kami.</p>
                <a class="button button--orange" href="{{ route('biatech.help') }}">Pusat Bantuan <x-biatech.icon name="arrow-right" :size="17" /></a>
                @if (config('biatech-storefront.support_email'))<a href="mailto:{{ config('biatech-storefront.support_email') }}" class="footer-contact__email">{{ config('biatech-storefront.support_email') }}</a>@endif
            </div>
        </div>
        <div class="footer-base"><div class="container"><span>© {{ date('Y') }} BIATECH. Seluruh hak dilindungi.</span><span>Dirancang untuk transaksi produk digital yang praktis.</span></div></div>
    </footer>
    <script defer src="{{ asset('biatech/js/storefront.js') }}"></script>
    @livewireScripts
</body>
</html>
