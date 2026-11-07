@extends('layouts.biatech')
@section('title', $product['name'].' — BIATECH')
@section('content')
<section class="page-section" style="background:#fafbfe">
    <div class="container">
        <nav class="breadcrumb" style="color:#587086;margin-bottom:24px" aria-label="Breadcrumb">
            <a href="{{ route('biatech.home') }}">Beranda</a><span class="breadcrumb__sep">/</span><a href="{{ route('biatech.catalog') }}">Katalog</a><span class="breadcrumb__sep">/</span><span style="color:#163c56">{{ $product['name'] }}</span>
        </nav>
        <div class="product-layout">
            <div class="product-detail-art tone-{{ $product['tone'] }}">
                <div class="product-detail-art__glow"></div>
                <div class="product-detail-art__disc"><x-biatech.icon :name="$product['icon']" :size="112" /></div>
                <span class="product-detail-art__chip"><x-biatech.icon name="check-circle" :size="17" /> {{ $product['category_name'] }}</span>
            </div>
            <div class="detail-info">
                <span class="section-kicker">{{ strtoupper($product['category_name']) }} · {{ strtoupper($product['provider']) }}</span>
                <h1>{{ $product['name'] }}</h1>
                <p class="detail-info__desc">{{ $product['description'] }}</p>
                <div class="detail-chips">
                    @if($product['quota'])<span class="detail-chip"><x-biatech.icon name="check-circle" :size="16" /> {{ $product['quota'] }}</span>@endif
                    @if($product['validity_days'])<span class="detail-chip"><x-biatech.icon name="clock" :size="16" /> {{ $product['validity_days'] }} hari</span>@endif
                    <span class="detail-chip"><x-biatech.icon name="shield" :size="16" /> Konfirmasi tujuan sebelum bayar</span>
                </div>
                <div class="detail-price"><small>Harga {{ config('biatech-storefront.catalog_source') === 'demo' ? 'simulasi' : 'jual' }}</small><strong>Rp{{ number_format($product['price'], 0, ',', '.') }}</strong></div>
                @if(count($product['countries']))<p style="margin:12px 0 0;font-size:12px"><strong>Area penggunaan:</strong> {{ implode(', ', $product['countries']) }}</p>@endif
                <p style="margin:16px 0;font-size:12px"><strong>Ketentuan:</strong> {{ $product['terms'] }}</p>
                <a class="button button--orange button--full button--lg" href="{{ route('biatech.checkout', $product['slug']) }}">Lanjut ke Checkout <x-biatech.icon name="arrow-right" :size="18" /></a>
                <div class="summary-assurance"><x-biatech.icon name="lock" :size="17" /> Preview: tidak ada pemotongan saldo atau pemesanan supplier pada tahap frontend.</div>
            </div>
        </div>
        <div class="details-section">
            <article class="panel"><h3>Yang perlu disiapkan</h3><ul class="bullets"><li><x-biatech.icon name="check" :size="16" /> Nomor tujuan atau ID pelanggan yang benar.</li><li><x-biatech.icon name="check" :size="16" /> Email aktif untuk referensi pelacakan demo.</li><li><x-biatech.icon name="check" :size="16" /> Pastikan paket sesuai provider dan wilayah penggunaan.</li></ul></article>
            <article class="panel"><h3>Informasi penting</h3><p>Harga dan data produk dalam mode demo digunakan hanya untuk pengembangan antarmuka. Status transaksi riil akan tersedia setelah Midtrans dan supplier diintegrasikan.</p><a href="{{ route('biatech.help') }}" class="text-link">Buka pusat bantuan <x-biatech.icon name="arrow-right" :size="16" /></a></article>
        </div>
        @if(count($related))
            <section style="margin-top:53px"><div class="section-heading"><div><span class="section-kicker">MASIH SATU KATEGORI</span><h2>Produk <em>Serupa</em></h2></div><a href="{{ route('biatech.catalog', ['category'=>$product['category']]) }}" class="text-link">Jelajahi kategori <x-biatech.icon name="arrow-right" :size="16" /></a></div><div class="featured-grid">@foreach(array_slice($related,0,4) as $item)<x-biatech.product-card :product="$item" />@endforeach</div></section>
        @endif
    </div>
</section>
@endsection
