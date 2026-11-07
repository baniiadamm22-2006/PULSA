@extends('layouts.biatech')
@section('title', 'Pembayaran Demo '.$invoice.' — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><span>Simulasi pembayaran</span></nav><span class="section-kicker">CHECKOUT · MODE DEMO</span><h1>Simulasi <em>Pembayaran</em></h1><p>Uji tampilan status pembayaran dengan skenario simulasi. Tidak ada dana yang ditagih.</p></div></section>
<section class="page-section"><div class="container">@livewire('biatech.payment-simulator', ['invoice'=>$invoice])</div></section>
@endsection
