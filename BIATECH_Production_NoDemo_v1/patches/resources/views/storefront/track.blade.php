@extends('layouts.biatech')
@section('title', 'Informasi Pesanan — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><span>Informasi Pesanan</span></nav><span class="section-kicker">STATUS LAYANAN</span><h1>Informasi <em>Pesanan</em></h1><p>Pelacakan pesanan online belum tersedia.</p></div></section>
<section class="page-section"><div class="container"><div class="panel panel--padded"><h2>Fitur sedang disiapkan</h2><p>Pelacakan akan tersedia setelah sistem pesanan dan verifikasi kepemilikan pesanan terintegrasi. Tidak ada data pesanan sementara yang ditampilkan di halaman ini.</p><a class="button button--orange" href="{{ route('biatech.help') }}">Pusat Bantuan <x-biatech.icon name="arrow-right" :size="17" /></a></div></div></section>
@endsection
