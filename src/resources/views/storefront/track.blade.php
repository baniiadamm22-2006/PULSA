@extends('layouts.biatech')
@section('title', 'Lacak Pesanan — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><span>Lacak pesanan</span></nav><span class="section-kicker">LACAK PEMBELIANMU</span><h1>Lacak <em>Pesanan</em></h1><p>Masukkan nomor invoice dan email yang digunakan saat checkout demo untuk melihat hasil simulasi.</p></div></section>
<section class="page-section"><div class="container">@livewire('biatech.track-order')</div></section>
@endsection
