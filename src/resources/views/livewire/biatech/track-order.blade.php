<div class="content-grid">
    <article class="panel panel--padded"><h2>Cari Invoice</h2><p>Data hanya dapat ditemukan dari pesanan yang dibuat di sesi browser yang sama.</p>
        <form wire:submit="search" novalidate>
            <div class="form-field"><label for="track-invoice" class="field-label">Nomor invoice</label><input id="track-invoice" class="field-control" type="text" wire:model="invoice" placeholder="BT-DEMO-XXXXXXXXXX">@error('invoice')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="form-field"><label for="track-email" class="field-label">Email checkout</label><input id="track-email" class="field-control" type="email" wire:model="email" placeholder="email@contoh.com">@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            <button class="button button--orange button--full" type="submit" wire:loading.attr="disabled" wire:target="search"><x-biatech.icon name="search" :size="17" /> Cari Pesanan Demo</button>
        </form>
        @if($searched)
            <div class="status-result" role="status">
                @if($foundOrder)
                    <div class="status-view"><div class="status-symbol"><x-biatech.icon name="check-circle" :size="26" /></div><div><span class="section-kicker">PESANAN DITEMUKAN</span><h3 style="margin:7px 0">{{ $foundOrder['product_name'] }}</h3><span class="payment-status payment-status--{{ $foundOrder['payment_status'] }}">{{ strtoupper($foundOrder['payment_status']) }}</span></div></div>
                    <div class="summary-line" style="margin-top:10px"><span>Total demo</span><strong>Rp{{ number_format($foundOrder['amount'],0,',','.') }}</strong></div>
                    <a href="{{ route('biatech.order',$foundOrder['invoice']) }}" class="button button--navy" style="margin-top:10px">Lihat Detail <x-biatech.icon name="arrow-right" :size="16" /></a>
                @else
                    <div class="notice"><x-biatech.icon name="alert-circle" :size="18" /><span>Pesanan tidak ditemukan dalam sesi ini, atau email tidak cocok. Data demo tidak disimpan permanen.</span></div>
                @endif
            </div>
        @endif
    </article>
    <aside><div class="support-box"><x-biatech.icon name="shield" :size="34" /><h3>Pelacakan lebih aman</h3><p>Untuk implementasi produksi, fitur ini harus menggunakan verifikasi tambahan seperti OTP atau token rahasia, serta rate limiting.</p><a class="button button--white" href="{{ route('biatech.help') }}">Baca panduan <x-biatech.icon name="arrow-right" :size="17" /></a></div><div class="notice" style="margin-top:15px"><x-biatech.icon name="info" :size="20" /> Demo hanya menyimpan pesanan sementara. Nomor invoice dari pengguna lain tidak bisa dicari di sini.</div></aside>
</div>
