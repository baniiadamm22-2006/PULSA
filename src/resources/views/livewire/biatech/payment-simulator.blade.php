<div class="content-grid">
    <div>
        <div class="simulator-box shine">
            <span class="section-kicker">PAYMENT SANDBOX VISUAL</span>
            <h3 style="font-size:24px;margin:9px 0 9px">Pilih skenario untuk preview</h3>
            <p>Semua tombol berikut mengubah data sesi lokal untuk mendemonstrasikan tampilan, <strong style="color:#fff">bukan melakukan pembayaran maupun mengirim pulsa/token</strong>.</p>
            <div class="scenario-grid">
                <button type="button" wire:click="simulate('pending')">Menunggu</button>
                <button type="button" wire:click="simulate('success')">Berhasil (demo)</button>
                <button type="button" wire:click="simulate('failed')">Gagal (demo)</button>
                <button type="button" wire:click="simulate('expired')">Kedaluwarsa</button>
                <button type="button" wire:click="simulate('review')">Perlu review</button>
            </div>
            <span class="field-hint" wire:loading.delay wire:target="simulate" role="status">Memperbarui simulasi...</span>
        </div>
        <article class="panel">
            <div class="status-view"><div class="status-symbol"><x-biatech.icon name="credit-card" :size="30" /></div><div><span class="section-kicker">INVOICE {{ $invoice }}</span><h2 style="margin:6px 0">Status Pembayaran</h2><span class="payment-status payment-status--{{ $order['payment_status'] }}">{{ strtoupper($order['payment_status']) }}</span></div></div>
            <p style="margin-top:20px">{{ match($order['payment_status']) { 'paid' => 'Pada skenario demo, pembayaran ditandai berhasil.', 'failed' => 'Skenario pembayaran gagal. Tidak ada produk yang dikirim.', 'expired' => 'Skenario pembayaran melewati batas waktu.', default => 'Pembayaran masih menunggu dalam simulasi.' } }}</p>
            <div class="notice"><x-biatech.icon name="info" :size="18" /><span>Belum ada transaksi Midtrans. Perubahan status ini bukan bukti pembayaran asli.</span></div>
            <div class="status-result"><a href="{{ route('biatech.order',$invoice) }}" class="button button--orange">Lihat Detail Pesanan <x-biatech.icon name="arrow-right" :size="17" /></a> <a href="{{ route('biatech.track') }}" class="button button--outline">Lacak Invoice</a></div>
        </article>
    </div>
    <aside class="summary-card sticky-summary"><h3>Rincian Transaksi Demo</h3><div class="summary-line"><span>Invoice</span><strong>{{ $invoice }}</strong></div><div class="summary-line"><span>Produk</span><strong>{{ $order['product_name'] }}</strong></div><div class="summary-line"><span>Nomor tujuan</span><strong>{{ substr($order['destination'],0,4) }}••••{{ substr($order['destination'],-3) }}</strong></div><div class="summary-line"><span>Status order</span><strong>{{ str_replace('_',' ',strtoupper($order['order_status'])) }}</strong></div><div class="summary-line summary-line--total"><span>Total</span><strong>Rp{{ number_format($order['amount'],0,',','.') }}</strong></div><div class="summary-assurance"><x-biatech.icon name="lock" :size="17" /> Data sementara tersimpan di sesi browser yang sama.</div></aside>
</div>
