@extends('layouts.biatech')
@section('title', 'Informasi Pembelian ' . $product['name'] . ' — BIATECH')
@section('content')
<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><a href="{{ route('biatech.product', $product['slug']) }}">Detail Produk</a><span>/</span><span>Informasi Pembelian</span></nav>
        <span class="section-kicker">INFORMASI LAYANAN</span>
        <h1>Layanan <em>Pembelian</em></h1>
        <p>Fitur pembelian online sedang dalam persiapan.</p>
    </div>
</section>
<section class="page-section">
    <div class="container">
        <div class="panel panel--padded">
            <h2>{{ $product['name'] }}</h2>
            <p>Pemesanan dan pembayaran online untuk produk ini belum tersedia. Tidak ada pesanan yang dibuat atau pembayaran yang ditagihkan dari halaman ini.</p>
            <a class="button button--orange" href="{{ route('biatech.catalog') }}">Kembali ke Katalog <x-biatech.icon name="arrow-right" :size="17" /></a>
        </div>
    </div>
</section>
@endsection
