# BIATECH Admin Dashboard Widgets v1

Target: Laravel 12, Filament v3, namespace panel `App\Filament\Admin\Widgets`.

Dua widget baru, tanpa menimpa `AdminPanelProvider.php` atau dashboard bawaan:
- BiatechOperationalStats: 4 kartu dari tabel `orders`, `payments`, `order_items`, `transactions`; deposit reseller masih `—`.
- BiatechIntegrationStatus: 3 kartu belum terverifikasi (informasi status implementasi, bukan ping koneksi).

Catatan keuangan:
- Pembayaran Lunas Tercatat = jumlah `payments.amount` dengan `status` `paid`/`settlement` (dan gateway `midtrans` bila kolom tersedia). Bukan saldo Midtrans, bukan dana cair, tidak otomatis dikurangi refund.
- Estimasi Margin Produk = (harga jual snapshot - modal snapshot) x quantity untuk order_item yang punya sekurang-kurangnya satu transaksi berstatus `success`. Bukan laba bersih. Tidak termasuk biaya payment gateway, pajak, biaya operasional, dan refund.
- Widget membaca database (SELECT) saja. Tidak membuat produk, saldo, transaksi, atau migrasi.
- Nilai `Belum tersedia` berarti tabel atau kolom yang diperlukan belum ada; nilai `Rp0` berarti query berhasil dan totalnya nol.

## Pemasangan

Ekstrak ZIP ke dalam `/root/perkuliahan/pulsa`.

```bash
cd /root/perkuliahan/pulsa
bash BIATECH_Admin_Dashboard_Widgets_v1/install.sh --check
bash BIATECH_Admin_Dashboard_Widgets_v1/install.sh --apply

docker compose exec -T php php -l app/Filament/Admin/Widgets/BiatechOperationalStats.php
docker compose exec -T php php -l app/Filament/Admin/Widgets/BiatechIntegrationStatus.php
docker compose exec -T php php artisan optimize:clear

docker compose exec -T php php artisan tinker --execute='dump(array_values(array_filter(\Filament\Facades\Filament::getPanel("admin")->getWidgets(), fn ($widget) => str_contains($widget, "Biatech"))));'
```

Lalu buka `https://pulsa.test/admin` dan refresh.

**Jangan ubah `AdminPanelProvider.php`**. Versi yang user kirim sudah memanggil `->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')` dan `Pages\Dashboard::class`.

## Keamanan
- `--apply` berhenti jika target sudah ada; tidak overwrite file lama.
- Jangan memakai status `Belum terverifikasi` sebagai health check aktif. Setelah API siap, tambahkan verifikasi terjadwal dan waktu sinkron terakhir.
- Hanya uji otomatis dengan database terpisah dari produksi.
