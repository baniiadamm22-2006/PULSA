@extends('layouts.biatech')
@section('title', 'BIATECH — Pulsa, Paket Data, Roaming, Token PLN')
@section('content')
<section class="hero-section">
    <div class="container hero-layout">
        <div class="hero-copy">
            <span class="eyebrow"><x-biatech.icon name="sparkles" :size="15" /> SEMUA KEBUTUHAN DIGITALMU</span>
            <h1>SEMUA KEBUTUHAN <span>DIGITAL,</span> SATU TEMPAT.</h1>
            <p>Beli pulsa, paket internet, roaming internasional, paket Umroh, hingga token listrik. Pilih produk yang kamu butuhkan dengan proses simpel dan transparan.</p>
            <div class="hero-buttons">
                <a class="button button--orange button--lg" href="{{ route('biatech.catalog') }}">JELAJAHI PRODUK <x-biatech.icon name="arrow-right" :size="20" /></a>
                <a class="button button--white button--lg" href="{{ route('biatech.track') }}"><x-biatech.icon name="clipboard" :size="19" /> Lacak Pesanan</a>
            </div>
            <div class="hero-highlights">
                <span><x-biatech.icon name="check-circle" :size="20" /> Tanpa daftar akun</span>
                <span><x-biatech.icon name="shield" :size="20" /> Alur transaksi jelas</span>
                <span><x-biatech.icon name="headset" :size="20" /> Akses bantuan</span>
            </div>
        </div>
        <div class="hero-art"><div class="hero-art__halo"></div><img src="{{ asset('biatech/images/hero-connect.svg') }}" alt="Ilustrasi layanan digital BIATECH di smartphone" width="700" height="600"><span class="hero-art__tag hero-art__tag--top"><x-biatech.icon name="wifi" :size="17" /> Internet & Pulsa</span><span class="hero-art__tag hero-art__tag--bottom"><x-biatech.icon name="zap" :size="17" /> Token PLN</span></div>
    </div>
</section>

<section class="benefits-strip"><div class="container benefits-grid">
    <div><x-biatech.icon name="smartphone" :size="28" /><span><strong>Tanpa Registrasi</strong><small>Mulai pembelian tanpa buat akun</small></span></div>
    <div><x-biatech.icon name="shield" :size="28" /><span><strong>Alur Pembayaran</strong><small>Informasi pembayaran terstruktur</small></span></div>
    <div><x-biatech.icon name="clipboard" :size="28" /><span><strong>Lacak Transaksi</strong><small>Pantau status pembelian</small></span></div>
    <div><x-biatech.icon name="headset" :size="28" /><span><strong>Pusat Bantuan</strong><small>Informasi dan panduan layanan</small></span></div>
</div></section>

<section class="section container" id="kategori">
    <div class="section-heading"><div><span class="section-kicker">KATEGORI LAYANAN</span><h2>Belanja Berdasarkan <em>Kategori</em></h2><p>Pilih produk digital sesuai kebutuhanmu.</p></div><a href="{{ route('biatech.catalog') }}" class="text-link">Lihat semua <x-biatech.icon name="arrow-right" :size="17" /></a></div>
    <div class="category-grid">@foreach($categories as $category)<x-biatech.category-card :category="$category" />@endforeach</div>
</section>

<section class="container"><div class="orange-promo"><div class="orange-promo__graphic"><x-biatech.icon name="zap" :size="76" /></div><div><span class="orange-promo__eyebrow">MULAI DALAM HITUNGAN LANGKAH</span><h2>ISI PULSA, DATA & TOKEN<br><span>LEBIH PRAKTIS!</span></h2><p>5 jenis layanan digital · checkout tanpa akun · status pesanan yang dapat dilacak</p></div><a href="{{ route('biatech.catalog') }}" class="button button--white button--lg">Cari Produk <x-biatech.icon name="arrow-right" :size="20" /></a></div></section>

<section class="section container">
    <div class="section-heading"><div><span class="section-kicker">PILIHAN BIATECH</span><h2>Produk <em>Pilihan</em></h2><p>Jelajahi layanan digital favoritmu. Harga demo hanya untuk contoh tampilan.</p></div><a href="{{ route('biatech.catalog') }}" class="text-link">Semua produk <x-biatech.icon name="arrow-right" :size="17" /></a></div>
    @if (count($featured))<div class="featured-grid">@foreach($featured as $product)<x-biatech.product-card :product="$product" :large="$loop->index < 2" />@endforeach</div>
    @else <div class="empty-state"><x-biatech.icon name="search" :size="44" /><h3>Produk belum tersedia</h3><p>Tambahkan produk aktif dari Admin Panel Filament.</p></div>@endif
</section>

<section class="steps-section"><div class="container"><div class="section-heading section-heading--center"><div><span class="section-kicker">CARA PEMBELIAN</span><h2>Cuma <em>3 Langkah</em></h2><p>Alur sederhana dari pilih produk sampai pantau status.</p></div></div><div class="steps-grid"><article><span class="step-number">01</span><span class="step-icon"><x-biatech.icon name="grid" :size="32" /></span><h3>Pilih Produk</h3><p>Temukan layanan dan paket yang kamu inginkan.</p></article><article><span class="step-number">02</span><span class="step-icon"><x-biatech.icon name="clipboard" :size="32" /></span><h3>Isi Data Tujuan</h3><p>Masukkan nomor HP atau ID pelanggan dengan benar.</p></article><article><span class="step-number">03</span><span class="step-icon"><x-biatech.icon name="check-circle" :size="32" /></span><h3>Bayar & Pantau</h3><p>Ikuti instruksi pembayaran dan lacak status pesanan.</p></article></div></div></section>

<section class="container cta-section"><div><span class="section-kicker">BIATECH DIGITAL</span><h2>Siap memenuhi kebutuhan digitalmu?</h2><p>Temukan produk yang tepat dan mulai dari sekarang.</p></div><a class="button button--orange button--lg" href="{{ route('biatech.catalog') }}">Jelajahi Katalog <x-biatech.icon name="arrow-right" :size="19" /></a></section>
@endsection
