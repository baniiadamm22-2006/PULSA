@extends('layouts.biatech')
@section('title', 'Katalog Produk Digital — BIATECH')
@section('content')
<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span class="breadcrumb__sep">/</span><span>Katalog produk</span></nav>
        <span class="section-kicker">LENGKAP DALAM SATU TEMPAT</span>
        <h1>Temukan <em>Produk Digital</em></h1>
        <p>Dari pulsa dan paket internet sampai roaming dan token PLN. Filter sesuai kebutuhan, lalu pilih paket yang paling cocok.</p>
    </div>
</section>
<section class="page-section">
    <div class="container">
        @livewire('biatech.catalog-browser')
    </div>
</section>
@endsection
