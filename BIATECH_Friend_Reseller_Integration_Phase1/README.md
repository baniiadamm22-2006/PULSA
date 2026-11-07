# BIATECH – Friend Reseller API (Phase 1, Read-only)

**Tujuan:** tahap awal integrasi API reseller teman Anda pada Laravel 12 tanpa memengaruhi login Filament, landing page, database, Midtrans, ataupun saldo asli. Tahap ini **tidak** melakukan pengiriman order, mengubah harga, mengisi saldo, atau mengirim Telegram.

Paket hanya **menambahkan** tiga file (config, service client, Artisan command). Jika nama file sudah ada, installer berhenti dan tidak menimpa apa pun.

## 1. Instalasi

Ekstrak ZIP sehingga `BIATECH_Friend_Reseller_Integration_Phase1/` berada di `/root/perkuliahan/pulsa`.

```bash
cd /root/perkuliahan/pulsa
bash BIATECH_Friend_Reseller_Integration_Phase1/install.sh --check
bash BIATECH_Friend_Reseller_Integration_Phase1/install.sh --apply
```

Jangan lakukan `--apply` jika `--check` tidak menghasilkan `CHECK OK`.

## 2. Konfigurasi

Tambahkan ke `src/.env`. **Endpoint di bawah adalah placeholder yang wajib diganti sesuai dokumentasi API teman Anda; bukan endpoint yang telah terverifikasi.**

```dotenv
FRIEND_RESELLER_ENABLED=false
FRIEND_RESELLER_BASE_URL=https://api.domain-teman.example
FRIEND_RESELLER_TOKEN=isi_token_asli_di_local_env
FRIEND_RESELLER_AUTH_MODE=bearer
FRIEND_RESELLER_AUTH_HEADER=X-API-Key
FRIEND_RESELLER_CATALOG_PATH=/api/products
FRIEND_RESELLER_BALANCE_PATH=/api/balance
FRIEND_RESELLER_CATALOG_ITEMS_FIELD=data
FRIEND_RESELLER_BALANCE_AMOUNT_FIELD=data.balance
```

`FRIEND_RESELLER_AUTH_MODE=header` dipakai jika server teman menggunakan header API key, misalnya `X-API-Key`, bukan Bearer. `*_FIELD` adalah dot path yang mengikuti struktur JSON response dari teman Anda.

Pastikan token tidak pernah dikirim ke chat atau commit Git. Setelah endpoint, autentikasi, dan token valid, ubah `FRIEND_RESELLER_ENABLED=true`.

## 3. Testing

```bash
cd /root/perkuliahan/pulsa
docker compose exec -T php php artisan config:clear
```

```bash
docker compose exec -T php php artisan biatech:reseller-check
docker compose exec -T php php artisan biatech:reseller-check --catalog
docker compose exec -T php php artisan biatech:reseller-check --balance
```

Client hanya GET JSON via HTTPS, tidak mengikuti redirect, dan tidak menulis data. Jika endpoint JSON belum sesuai, respons command akan menunjukkan apa yang perlu disesuaikan tanpa mengeluarkan payload penuh.

## 4. Setelah API teman tersedia

Tahap berikutnya akan menambahkan **synchronizer** data katalog `supplier_products` berdasarkan `supplier_product_code`, perhitungan harga jual dengan margin minimum, status tersedia, update saldo pada widget Filament, kemudian workflow Midtrans & fulfillment Telegram. Semua ini **belum** disediakan oleh paket Phase 1 agar tidak ada asumsi terhadap kontrak API teman.

Midtrans dan saldo reseller adalah dua saldo terpisah; pembayaran yang `settlement` tidak berarti deposit reseller bertambah otomatis. Untuk pengiriman cepat, saldo reseller perlu dipraisi secara terpisah atau lewat mekanisme top-up yang memang disediakan partner.

## 5. Rollback

Tiga file baru bisa dihapus setelah memeriksa `git status` untuk memastikan memang file dari paket ini; `src/.env` edit manual. Tidak ada migration atau database change pada Phase 1.
