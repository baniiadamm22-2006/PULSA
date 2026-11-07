<div>
    <div class="checkout-steps"><span class="is-current"><span class="step-bubble">1</span> Isi tujuan</span><span>→</span><span><span class="step-bubble">2</span> Pembayaran</span><span>→</span><span><span class="step-bubble">3</span> Status transaksi</span></div>
    <div class="content-grid">
        <div class="panel panel--padded">
            <h2>Data Pembelian</h2><p>Checkout tanpa akun. Masukkan tujuan produk dan email yang dapat kamu akses.</p>
            @if($errors->any())<div class="error-banner" role="alert">Periksa kembali kolom yang ditandai sebelum melanjutkan.</div>@endif
            <form wire:submit="submit" novalidate>
                <div class="form-field">
                    <label for="checkout-destination" class="field-label">{{ $product['category'] === 'token-listrik-pln' ? 'Nomor meter / ID pelanggan PLN' : 'Nomor HP tujuan' }} <span style="color:var(--orange)">*</span></label>
                    <input id="checkout-destination" class="field-control" type="text" inputmode="numeric" autocomplete="off" wire:model="destination" placeholder="{{ $product['category'] === 'token-listrik-pln' ? 'Contoh: 123456789012' : 'Contoh: 081234567890' }}" aria-invalid="@error('destination') true @else false @enderror">
                    @error('destination')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    <span class="field-hint">Pastikan data tujuan sudah benar. Produk digital umumnya tidak dapat ditarik setelah diproses.</span>
                </div>
                <div class="form-field">
                    <label for="checkout-email" class="field-label">Email untuk pelacakan <span style="color:var(--orange)">*</span></label>
                    <input id="checkout-email" class="field-control" type="email" inputmode="email" autocomplete="email" wire:model="email" placeholder="email@contoh.com" aria-invalid="@error('email') true @else false @enderror">
                    @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                </div>
                <div class="form-note"><x-biatech.icon name="info" :size="20" /><span>Mode demo: pesanan hanya disimpan sementara di sesi browser. Tidak ada koneksi ke gateway pembayaran atau supplier.</span></div>
                <div class="form-field"><label class="checkbox-line"><input type="checkbox" wire:model="agreed"><span>Saya sudah memeriksa produk, harga simulasi, dan nomor tujuan serta memahami bahwa transaksi ini hanya simulasi UI.</span></label>@error('agreed')<span class="field-error" role="alert">{{ $message }}</span>@enderror</div>
                <button class="button button--orange button--lg button--full" type="submit" wire:loading.attr="disabled" wire:target="submit"><span wire:loading.remove wire:target="submit">Buat Pesanan Demo</span><span wire:loading wire:target="submit">Membuat pesanan...</span><x-biatech.icon name="arrow-right" :size="18" /></button>
            </form>
        </div>
        <aside class="summary-card sticky-summary"><h3>Ringkasan Pesanan</h3><div class="summary-line"><span>Produk</span><strong>{{ $product['name'] }}</strong></div><div class="summary-line"><span>Provider</span><strong>{{ $product['provider'] }}</strong></div><div class="summary-line"><span>Jumlah</span><strong>1 produk</strong></div><div class="summary-line"><span>Biaya layanan demo</span><strong>Rp0</strong></div><div class="summary-line summary-line--total"><span>Total simulasi</span><strong>Rp{{ number_format($product['price'],0,',','.') }}</strong></div><div class="summary-assurance"><x-biatech.icon name="shield" :size="18" /> Harga akhir dan metode pembayaran asli akan ditentukan sistem backend setelah integrasi aktif.</div><a class="text-link" style="margin-top:20px" href="{{ route('biatech.product',$product['slug']) }}"><x-biatech.icon name="arrow-left" :size="15" /> Kembali ke detail</a></aside>
    </div>
</div>
