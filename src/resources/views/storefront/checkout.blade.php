@extends('layouts.biatech')
@section('title', 'Checkout '.$product['name'].' — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><a href="{{ route('biatech.product',$product['slug']) }}">Detail Produk</a><span>/</span><span>Checkout</span></nav><span class="section-kicker">LANGKAH PEMBELIAN</span><h1>Konfirmasi <em>Pesanan</em></h1><p>Periksa nomor tujuan dan ringkasan pembelian sebelum melanjutkan.</p></div></section>
<section class="page-section"><div class="container">@livewire('biatech.checkout-form',['slug'=>$product['slug']])</div></section>
@endsection
