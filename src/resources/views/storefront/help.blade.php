@extends('layouts.biatech')
@section('title', 'Pusat Bantuan — BIATECH')
@section('content')
<section class="page-hero"><div class="container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('biatech.home') }}">Beranda</a><span>/</span><span>Bantuan</span></nav><span class="section-kicker">KAMI BANTU JELASKAN</span><h1>Pusat <em>Bantuan</em></h1><p>Panduan pembelian produk digital, pembayaran, serta status pesanan BIATECH.</p></div></section>
<section class="page-section"><div class="container content-grid">
    <div><div class="section-heading"><div><span class="section-kicker">PERTANYAAN UMUM</span><h2>Yang Sering <em>Ditanyakan</em></h2></div></div>
        <div class="faq-list">
            @foreach([
            ['Apakah harus membuat akun untuk membeli?','Tidak. BIATECH dirancang mendukung checkout tanpa registrasi. Pada versi demo, cukup masukkan nomor tujuan dan email.'],
            ['Bagaimana cara membeli pulsa atau paket data?','Pilih kategori, tentukan paket, masukkan nomor tujuan yang sesuai, kemudian konfirmasi ringkasan. Pembayaran nyata akan tersedia setelah integrasi Midtrans selesai.'],
            ['Bagaimana cara membeli token listrik PLN?','Pilih produk token listrik prabayar dan masukkan nomor meter atau ID pelanggan. Pastikan data sesuai sebelum melakukan pembayaran.'],
            ['Bagaimana cara melacak transaksi?','Pada mode demo, gunakan invoice dan email yang sama di browser yang sama. Di produksi, verifikasi tambahan akan diterapkan.'],
            ['Kenapa transaksi bisa berstatus pending atau review?','Pembayaran dan pengiriman produk adalah proses berbeda. Jika status supplier belum pasti, BIATECH perlu melakukan inquiry sebelum mengulangi transaksi.'],
            ['Apakah pembayaran di prototype sudah aktif?','Belum. Tombol status pada halaman pembayaran hanya mengubah simulasi UI dan tidak menghubungi payment gateway atau supplier.'],
            ['Bagaimana jika pembayaran berhasil tetapi produk gagal dikirim?','Sistem produksi memerlukan pemeriksaan status supplier dan kebijakan refund yang disahkan. Refund tidak boleh dinyatakan selesai sebelum benar-benar terkonfirmasi.'],
            ] as $index=>$faq)
                <details class="faq-item" @if($index===0) open @endif><summary>{{ $faq[0] }}</summary><p>{{ $faq[1] }}</p></details>
            @endforeach
        </div>
    </div>
    <aside><div class="support-box"><x-biatech.icon name="headset" :size="36" /><h3>Butuh informasi lain?</h3><p>Lihat status pesanan yang pernah kamu buat, atau hubungi kanal resmi ketika sudah diaktifkan.</p><a class="button button--white" href="{{ route('biatech.track') }}">Lacak Pesanan <x-biatech.icon name="arrow-right" :size="17" /></a>
        @if(config('biatech-storefront.support_email'))<a style="color:#ffc796;font-size:12px;display:block;word-break:break-all" href="mailto:{{ config('biatech-storefront.support_email') }}">{{ config('biatech-storefront.support_email') }}</a>@endif
        </div><div class="panel" style="margin-top:15px"><h3>5 Layanan BIATECH</h3><ul class="bullets"><li><x-biatech.icon name="check" :size="16" /> Pulsa reguler</li><li><x-biatech.icon name="check" :size="16" /> Paket data internet</li><li><x-biatech.icon name="check" :size="16" /> Roaming internasional</li><li><x-biatech.icon name="check" :size="16" /> Roaming Umroh</li><li><x-biatech.icon name="check" :size="16" /> Token listrik PLN</li></ul></div></aside>
</div></section>
@endsection
