# BIATECH Branding — Logo dan Color Palette

Visual refresh berdasarkan gambar identitas BIATECH yang diberikan pemilik proyek.

## Palet

- Cyan `#00D4FF`
- Blue `#3B82F6`
- Violet `#8B5CF6`
- Dark Navy `#0B102F`

## Cakupan

- Logo dari brand board di header dan footer storefront.
- Favicon BIATECH.
- Layer CSS tambahan untuk mengganti oranye dengan gradasi cyan/biru/ungu, **tanpa mengubah struktur HTML/CSS dasar**.
- Logo dan aksen biru untuk panel Admin Filament v3 ketika `AdminPanelProvider.php` masih menggunakan anchor yang dikenali.
- Tidak menyentuh routes/web.php, `.env`, database, CRUD, Livewire, migration, atau checkout.

## Instalasi (WSL)

Ekstrak folder `BIATECH_Branding_Logo_v1` ke dalam `/root/perkuliahan/pulsa`.

```bash
cd /root/perkuliahan/pulsa
python3 BIATECH_Branding_Logo_v1/install.py --root . --check
python3 BIATECH_Branding_Logo_v1/install.py --root . --apply

docker compose exec -T php php -l app/Providers/Filament/AdminPanelProvider.php
docker compose exec -T php php artisan view:clear
docker compose exec -T php php artisan optimize:clear
```

Periksa halaman `/`, `/produk`, `/admin/login`, dan `/admin` (setelah login). Gunakan Ctrl+Shift+R agar browser mengambil brand-theme.css baru.

## Backup dan rollback

Script otomatis menulis backup **di luar folder Git**, ke direktori saudara root (`/root/perkuliahan/pulsa-branding-backup-...`). Script akan menampilkan perintah rollback dengan path backup yang sesuai. Rollback menolak file yang berubah setelah instalasi untuk melindungi perubahan lokal.

## Catatan

- Jika `--check` gagal karena markup berbeda, jangan paksa; bagikan file `src/resources/views/layouts/biatech.blade.php` dan `src/app/Providers/Filament/AdminPanelProvider.php` (tanpa kredensial) agar patch dapat disesuaikan.
- File gambar ikon berasal dari crop asli brand board milik pemilik proyek. Wordmark horizontal admin dirangkai dengan ikon tersebut untuk keterbacaan di sidebar terang/gelap.
- Tidak perlu build Vite untuk branding storefront, karena brand-theme.css berada di `public`.
- Warna status (`success`, `danger`, `warning`) Filament tetap sesuai semantik; hanya `primary` yang berubah.
