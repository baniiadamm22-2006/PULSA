# BIATECH Filament v3 Login — isolation test

Ini adalah **file diagnostik sementara**, bukan konfigurasi panel production. Dibuat berdasarkan `AdminPanelProvider.php` yang dikirimkan: mempertahankan panel `admin`, autentikasi web, middleware session, discovery resources/pages/widgets, dan dashboard; sementara menonaktifkan seluruh plugin UI, SPA, custom profile, custom menu, dan custom theme untuk mengisolasi gangguan autentikasi.

## Pasang (project development)

Dari `/root/perkuliahan/pulsa`:

```bash
cp src/app/Providers/Filament/AdminPanelProvider.php src/app/Providers/Filament/AdminPanelProvider.php.pre_auth_test
cp BIATECH_Admin_Login_Isolation_v1/app/Providers/Filament/AdminPanelProvider.php src/app/Providers/Filament/AdminPanelProvider.php
docker compose exec -u www-data php php artisan optimize:clear
docker compose exec php php artisan route:list --path=admin
```

Lakukan tes di Edge InPrivate (`Ctrl+Shift+N`), langsung kunjungi `https://pulsa.test/admin/login`, lalu login. **Jangan** menonaktifkan `Authenticate` atau CSRF middleware. Jika berhasil, sumber masalah ada pada salah satu fitur/plugin yang dinonaktifkan (aktifkan satu per satu untuk mengidentifikasi). Jika gagal, lakukan audit cookie/browser dan alur `GET /admin`.

## Jika tetap gagal

Di DevTools Network, aktifkan **Keep log**, filter **All**, lalu login. Cek status `GET /admin` dan apakah setelah POST `/livewire/update` browser mengirim cookie session pada request dashboard. Jangan membagikan nilai cookie, header Authorization, atau payload password.

Jika request `GET /admin` kembali `302` ke `/admin/login`, periksa `SESSION_COOKIE`, `SESSION_DOMAIN`, `SESSION_PATH`, `SESSION_SECURE_COOKIE`, dan domain cookie di browser. Jika dashboard `500`, periksa error log baru. Jika dashboard `200` namun browser tetap login, periksa JavaScript/navigasi.

## Rollback

```bash
cp src/app/Providers/Filament/AdminPanelProvider.php.pre_auth_test src/app/Providers/Filament/AdminPanelProvider.php
docker compose exec -u www-data php php artisan optimize:clear
```

**Jangan** jalankan `migrate:fresh`, hapus `sessions`, reset `APP_KEY`, atau menonaktifkan middleware autentikasi untuk memperbaiki gejala ini.
