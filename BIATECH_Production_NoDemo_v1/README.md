# BIATECH — Cleanup demo/simulasi untuk storefront Laravel 12 + Filament 3

Paket ini dibuat dari arsip source BIATECH yang tersedia pada percakapan, **bukan** dari worktree WSL terbaru. Karena itu, installer melakukan pemeriksaan SHA-256 seluruh file sebelum mengubah apa pun. Jika file sudah dimodifikasi, proses akan **berhenti tanpa mengubah source**. Kirim daftar file yang berbeda untuk dibuatkan patch sesuai versi terbaru.

## Cakupan

- Menghapus katalog dummy dan pilihan mode demo: `CatalogService` membaca `categories`/`products` MariaDB saja.
- Membersihkan tulisan demo, harga simulasi, prototype pada halaman katalog, produk, beranda, bantuan, footer, dan CSS terkait.
- Menghapus Livewire `CheckoutForm`, `PaymentSimulator`, dan `TrackOrder` yang menggunakan session untuk transaksi palsu.
- Menghapus view pembayaran/pesanan simulasi; route lama `/pembayaran/{invoice}` dan `/pesanan/{invoice}` menghasilkan 404.
- Mempertahankan halaman checkout sebagai **informasi bahwa pembelian belum aktif**, tanpa tombol transaksi atau pembuatan order.
- Mempertahankan layout, admin panel, dan routing Livewire asli; `routes/web.php`, resource Filament, migration, dan `.env` tidak diubah.
- Menghapus tes fitur lama yang mengasumsikan demo, karena tes tersebut tidak lagi sesuai. Buat tes DB/payment sungguhan sesudah integrasi.

**Jangan gunakan patch ini untuk mengaktifkan penjualan secara langsung.** Integrasi pembayaran, webhook, idempotency, pemrosesan supplier dan kepemilikan pesanan tetap harus dibangun dan diuji.

## Instalasi aman di WSL

Ekstrak ZIP ke folder proyek `/root/perkuliahan/pulsa` sehingga tercipta folder `BIATECH_Production_NoDemo_v1`.

```bash
cd /root/perkuliahan/pulsa
python3 BIATECH_Production_NoDemo_v1/install.py --root /root/perkuliahan/pulsa --check
```

Jika CHECK OK, backup database terlebih dahulu dan pastikan website sedang tidak menangani transaksi sungguhan.

```bash
python3 BIATECH_Production_NoDemo_v1/install.py --root /root/perkuliahan/pulsa --apply

docker compose exec -T php php -l app/Services/Storefront/CatalogService.php
docker compose exec -T php php -l app/Http/Controllers/Storefront/StorefrontController.php
docker compose exec -T php php artisan optimize:clear

docker compose exec -T php php artisan route:list --path=pembayaran

git diff --check
```

Cek URL: `/`, `/produk`, `/produk/<slug>`, `/admin`, `/checkout/<slug>`. Pembayaran/pesanan simulasi harus 404, checkout hanya menyampaikan belum tersedia.

## Pemeriksaan lanjut sebelum go-live

```bash
python3 BIATECH_Production_NoDemo_v1/audit.py --root /root/perkuliahan/pulsa
```

Periksa hasil khususnya pada seeders/tes, kemudian tentukan apakah data yang sudah tersimpan di database adalah dummy; **jangan hapus otomatis** karena dapat terkait produk atau transaksi nyata. Filament bawaan mungkin memiliki seeders untuk Roles/Permissions/User yang wajib dipertahankan.

Backup otomatis disimpan di `../biatech-backups/production-cleanup-...` (di luar repo). Untuk rollback, gunakan perintah yang dicetak installer, contohnya:

```bash
python3 BIATECH_Production_NoDemo_v1/install.py --root /root/perkuliahan/pulsa --rollback /root/perkuliahan/biatech-backups/production-cleanup-YYYYMMDD-HHMMSS-xxxxxx
```

Setelah rollback, jalankan `docker compose exec -T php php artisan optimize:clear`.

## Pembatasan

Paket ini hanya menghilangkan mekanisme demo **di frontend berdasarkan arsip source yang tersedia**. Ini bukan audit penuh pada kode lokal terbaru dan bukan sertifikasi keamanan produksi. Form pembayaran nyata tidak akan aktif sampai backend transaksi selesai diintegrasikan.
