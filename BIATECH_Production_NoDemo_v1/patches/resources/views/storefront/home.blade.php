@extends('layouts.biatech')
@section('title', 'BIATECH — Pulsa, Paket Data, Roaming, Token PLN')
@section('content')
<section class="hero-section">
    <div class="container hero-layout">
        <div class="hero-copy">
            <span class="eyebrow"><x-biatech.icon name="sparkles" :size="15" /> SEMUA KEBUTUHAN DIGITALMU</span>
            <h1>SEMUA KEBUTUHAN <span>DIGITAL,</span> SATU TEMPAT.</h1>
            <p>Temukan produk pulsa, paket internet, roaming internasional, paket Umroh, dan token listrik dalam satu katalog BIATECH.</p>
            <div class="hero-buttons">
                <a class="button button--orange button--lg" href="{{ route('biatech.catalog') }}">JELAJAHI PRODUK <x-biatech.icon name="arrow-right" :size="20" /></a>
                <a class="button button--white button--lg" href="{{ route('biatech.help') }}"><x-biatech.icon name="headset" :size="19" /> Pusat Bantuan</a>
            </div>
            <div class="hero-highlights">
                <span><x-biatech.icon name="check-circle" :size="20" /> Informasi produk</span>
                <span><x-biatech.icon name="shield" :size="20" /> Harga katalog terbuka</span>
                <span><x-biatech.icon name="headset" :size="20" /> Akses bantuan</span>
            </div>
        </div>
        <div class="hero-art"><div class="hero-art__halo"></div><img src="{{ asset('biatech/images/hero-connect.svg') }}" alt="Ilustrasi layanan digital BIATECH di smartphone" width="700" height="600"><span class="hero-art__tag hero-art__tag--top"><x-biatech.icon name="wifi" :size="17" /> Internet & Pulsa</span><span class="hero-art__tag hero-art__tag--bottom"><x-biatech.icon name="zap" :size="17" /> Token PLN</span></div>
    </div>
</section>

<section class="benefits-strip"><div class="container benefits-grid">
    <div><x-biatech.icon name="smartphone" :size="28" /><span><strong>Katalog Produk</strong><small>Lihat produk tanpa membuat akun</small></span></div>
    <div><x-biatech.icon name="shield" :size="28" /><span><strong>Informasi Harga</strong><small>Harga produk dikelola melalui katalog</small></span></div>
    <div><x-biatech.icon name="clipboard" :size="28" /><span><strong>Informasi Layanan</strong><small>Rincian kategori dan produk</small></span></div>
    <div><x-biatech.icon name="headset" :size="28" /><span><strong>Pusat Bantuan</strong><small>Informasi dan panduan layanan</small></span></div>
</div></section>

<section class="section container" id="kategori">
    <div class="section-heading"><div><span class="section-kicker">KATEGORI LAYANAN</span><h2>Belanja Berdasarkan <em>Kategori</em></h2><p>Pilih produk digital sesuai kebutuhanmu.</p></div><a href="{{ route('biatech.catalog') }}" class="text-link">Lihat semua <x-biatech.icon name="arrow-right" :size="17" /></a></div>
    <div class="category-grid">@foreach($categories as $category)<x-biatech.category-card :category="$category" />@endforeach</div>
</section>

<section class="container"><div class="orange-promo"><div class="orange-promo__graphic"><x-biatech.icon name="zap" :size="76" /></div><div><span class="orange-promo__eyebrow">MULAI DALAM HITUNGAN LANGKAH</span><h2>ISI PULSA, DATA & TOKEN<br><span>LEBIH PRAKTIS!</span></h2><p>Informasi kategori, provider, dan harga produk digital BIATECH</p></div><a href="{{ route('biatech.catalog') }}" class="button button--white button--lg">Cari Produk <x-biatech.icon name="arrow-right" :size="20" /></a></div></section>

<section class="section container">
    <div class="section-heading"><div><span class="section-kicker">PILIHAN BIATECH</span><h2>Produk <em>Pilihan</em></h2><p>Jelajahi layanan digital favoritmu. Pilihan produk digital dari katalog BIATECH.</p></div><a href="{{ route('biatech.catalog') }}" class="text-link">Semua produk <x-biatech.icon name="arrow-right" :size="17" /></a></div>
    @if (count($featured))<div class="featured-grid">@foreach($featured as $product)<x-biatech.product-card :product="$product" :large="$loop->index < 2" />@endforeach</div>
    @else <div class="empty-state"><x-biatech.icon name="search" :size="44" /><h3>Produk belum tersedia</h3><p>Silakan periksa kembali katalog secara berkala.</p></div>@endif
</section>

<section class="steps-section"><div class="container"><div class="section-heading section-heading--center"><div><span class="section-kicker">CARA MEMILIH PRODUK</span><h2>Temukan <em>Produk Tepat</em></h2><p>Periksa detail produk sebelum memutuskan pembelian.</p></div></div><div class="steps-grid"><article><span class="step-number">01</span><span class="step-icon"><x-biatech.icon name="grid" :size="32" /></span><h3>Pilih Produk</h3><p>Temukan layanan dan paket yang kamu inginkan.</p></article><article><span class="step-number">02</span><span class="step-icon"><x-biatech.icon name="clipboard" :size="32" /></span><h3>Periksa Rincian</h3><p>Pastikan operator, nominal, dan informasi layanan sesuai.</p></article><article><span class="step-number">03</span><span class="step-icon"><x-biatech.icon name="check-circle" :size="32" /></span><h3>Dapatkan Informasi</h3><p>Hubungi pusat bantuan untuk informasi layanan terkini.</p></article></div></div></section>

<section class="container cta-section"><div><span class="section-kicker">BIATECH DIGITAL</span><h2>Siap memenuhi kebutuhan digitalmu?</h2><p>Temukan produk yang tepat dan mulai dari sekarang.</p></div><a class="button button--orange button--lg" href="{{ route('biatech.catalog') }}">Jelajahi Katalog <x-biatech.icon name="arrow-right" :size="19" /></a></section>
@endsection
