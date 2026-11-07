# BIATECH — Patch Resource Existing Filament v3

**Basis**: arsip `BIATECH_RESOURCE_ASLI.tar.gz` dari pengguna, Laravel 12 / Filament 3.3.10 / MariaDB.

Patch mengubah **file PHP yang sudah ada**, hanya di `src/app/Filament/Admin/Resources`. **Tidak membuat folder/Resource baru** pada aplikasi. **Tidak menyentuh** `AdminPanelProvider.php`, login boilerplate, `routes/web.php`, frontend, migrations, models, maupun database.

## Yang sudah lengkap di source asli, sengaja tidak ditimpa
- `CategoryResource.php`: nama, slug, deskripsi, urutan, aktif, tabel.
- `ProviderResource.php`: nama, kode, logo, aktif, tabel.
- `ProductResource.php`: kategori, provider, kode, nama, harga, kuota, negara roaming, aktif, tabel.
- `UserResource.php`: tetap original boilerplate.

## File yang diperbaiki

### Master data (CREATE/EDIT)
- `SupplierResource.php`: nama, kode, referensi konfigurasi, aktif.
- `SupplierProductResource.php`: pilih supplier/produk, SKU supplier, harga supplier, status aktif. Validasi uniqueness gabungan `supplier_id` dengan `product_id`/`supplier_product_code`.
- `SettingResource.php`: key, tipe nilai, value, is_public, dengan `updated_by` pada create/edit.

### Monitoring transaksi (LIST + VIEW modal, READ ONLY)
- `OrderResource.php`
- `OrderItemResource.php`
- `PaymentResource.php`
- `PaymentWebhookResource.php`
- `RefundResource.php`
- `TransactionResource.php`
- `TransactionLogResource.php`
- `AdminActivityLogResource.php`

Pada 8 Resource di atas, `getPages()` hanya mendaftarkan **index**, `canCreate` / `canEdit` / `canDelete` ditutup, dan tombol Create bawaan halaman List dihapus. File Create/Edit lama **tetap ada di folder lu** (tidak dihapus), tetapi route-nya tidak lagi didaftarkan. Kolom sensitif seperti customer_contact, destination, tracking_token_hash, dan payload_reference tidak ditampilkan. Data transaksi seharusnya dihasilkan lewat checkout/gateway/supplier, bukan dibuat manual di admin.

## Instalasi

Salin ZIP ke root project WSL `/root/perkuliahan/pulsa`, lalu:

```bash
cd /root/perkuliahan/pulsa
unzip -o BIATECH_RESOURCE_PATCH_ASLI.zip

# Pemeriksaan saja (tidak mengubah project)
bash BIATECH_RESOURCE_PATCH_ASLI/install.sh --check

# Terapkan (backup otomatis ke backups/biatech-resource-...)
bash BIATECH_RESOURCE_PATCH_ASLI/install.sh --apply

# Validasi PHP syntax
docker compose exec -T php sh -lc 'find app/Filament/Admin/Resources -name "*.php" -print0 | xargs -0 -n1 php -l'

# Bersihkan cache
docker compose exec -T -u www-data php php artisan optimize:clear

# Verifikasi route admin
docker compose exec -T php php artisan route:list --path=admin
```

## Uji langkah per langkah

1. `/admin/categories/create`: buat kategori aktif.
2. `/admin/providers/create`: buat provider aktif.
3. `/admin/products/create`: buat produk, pilih kategori/provider, aktifkan, simpan.
4. `/admin/suppliers/create`: buat supplier (default **tidak aktif**).
5. `/admin/supplier-products/create`: pilih supplier/produk dan mapping SKU.
6. `/admin/settings/create`: coba `key=site_name`, `value=BIATECH`, `value_type=string`, publik sesuai kebutuhan.
7. `/admin/order-items`: hanya List dan Detail, **tidak ada** tombol Create kosong.
8. `/produk`: periksa hasil produk. Mode `catalog_source=database` terkonfirmasi dari hasil Tinker sebelumnya; rendering frontend tidak bisa diuji karena source storefront tidak ada dalam arsip ini.

Untuk melihat data database setelah simpan:

```bash
docker compose exec -T php php artisan tinker --execute='dump([
  "categories" => App\Models\Category::count(),
  "providers" => App\Models\Provider::count(),
  "products" => App\Models\Product::count(),
  "suppliers" => App\Models\Supplier::count(),
  "supplier_products" => App\Models\SupplierProduct::count(),
  "settings" => App\Models\Setting::count(),
]);'
```

## Rollback

Installer mencetak path backup misalnya `/root/perkuliahan/pulsa/backups/biatech-resource-...`:

```bash
cd /root/perkuliahan/pulsa
bash BIATECH_RESOURCE_PATCH_ASLI/rollback.sh '/root/perkuliahan/pulsa/backups/biatech-resource-ISI_OUTPUT_INSTALLER'
docker compose exec -T -u www-data php php artisan optimize:clear
```

## Batasan yang perlu diketahui

- Syntax PHP seluruh file baru sudah diperiksa; uji Laravel/Docker nyata harus dijalankan di WSL pengguna.
- CRUD transaksi tidak diaktifkan: belum ada kode checkout/gateway yang tersedia pada arsip ini.
- Jika masih ada spinner, di DevTools → Network periksa `POST /livewire/update`; `419` berarti session/CSRF, `500` berarti error backend. Resource tidak bisa memperbaiki 419 sendiri.
- Package tidak menghapus atau mengubah data yang sudah ada.
