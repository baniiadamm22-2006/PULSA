@extends('layouts.biatech')
@section('title', 'Pusat Bantuan — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><span>Bantuan</span></nav><span class="section-kicker">INFORMASI LAYANAN</span><h1>Pusat <em>Bantuan</em></h1><p>Informasi seputar katalog produk dan fitur BIATECH.</p></div></section>
<section class="page-section"><div class="container content-grid">
    <div><div class="section-heading"><div><span class="section-kicker">PERTANYAAN UMUM</span><h2>Yang Sering <em>Ditanyakan</em></h2></div></div>
        <div class="faq-list">
            @foreach([
                ['Apa saja layanan yang ditampilkan?', 'BIATECH menampilkan kategori dan produk digital yang dikelola melalui katalog resmi aplikasi.'],
                ['Dari mana informasi harga produk berasal?', 'Harga produk berasal dari data katalog BIATECH. Harga dan ketersediaan dapat berubah sebelum fitur pembelian diaktifkan.'],
                ['Apakah pembelian dan pembayaran sudah dapat dilakukan?', 'Fitur pembelian dan pembayaran online belum diaktifkan. Sistem tidak membuat pesanan atau menagih pembayaran dari katalog saat ini.'],
                ['Bagaimana dengan pelacakan pesanan?', 'Pelacakan akan diaktifkan setelah sistem transaksi dan verifikasi pemilik pesanan selesai diintegrasikan.'],
                ['Bagaimana menghubungi BIATECH?', 'Gunakan kontak resmi yang ditampilkan pada bagian bantuan apabila tersedia.'],
            ] as $index => $faq)
                <details class="faq-item" @if($index === 0) open @endif><summary>{{ $faq[0] }}</summary><p>{{ $faq[1] }}</p></details>
            @endforeach
        </div>
    </div>
    <aside><div class="support-box"><x-biatech.icon name="headset" :size="36" /><h3>Butuh bantuan?</h3><p>Hubungi layanan pelanggan melalui kanal resmi BIATECH.</p>
        @if(config('biatech-storefront.support_email'))
            <a style="color:#ffc796;font-size:12px;display:block;word-break:break-all" href="mailto:{{ config('biatech-storefront.support_email') }}">{{ config('biatech-storefront.support_email') }}</a>
        @endif
        </div></aside>
</div></section>
@endsection
