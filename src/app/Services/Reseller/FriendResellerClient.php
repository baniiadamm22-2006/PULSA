<?php

declare(strict_types=1);

namespace App\Services\Reseller;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

final class FriendResellerClient
{
    /** @return array<string|int, mixed> */
    public function catalog(): array
    {
        return $this->get((string) config('biatech-reseller.catalog_path'));
    }

    /** @return array<string|int, mixed> */
    public function balance(): array
    {
        return $this->get((string) config('biatech-reseller.balance_path'));
    }

    /** @return array<string|int, mixed> */
    private function get(string $path): array
    {
        if (! config('biatech-reseller.enabled')) {
            throw new RuntimeException('Koneksi reseller dinonaktifkan.');
        }

        $baseUrl = trim((string) config('biatech-reseller.base_url'));
        $token = trim((string) config('biatech-reseller.token'));
        $mode = (string) config('biatech-reseller.auth_mode', 'bearer');

        if (
            parse_url($baseUrl, PHP_URL_SCHEME) !== 'https'
            || ! filter_var($baseUrl, FILTER_VALIDATE_URL)
            || parse_url($baseUrl, PHP_URL_USER) !== null
            || parse_url($baseUrl, PHP_URL_PASS) !== null
        ) {
            throw new InvalidArgumentException('FRIEND_RESELLER_BASE_URL harus URL HTTPS yang valid.');
        }

        if ($token === '') {
            throw new InvalidArgumentException('FRIEND_RESELLER_TOKEN belum diatur.');
        }

        // Prevent configured paths from overriding the trusted API host.
        $path = trim($path);

        if (
            $path === ''
            || str_starts_with($path, '//')
            || preg_match('/^[a-z][a-z0-9+.-]*:/i', $path)
            || str_contains($path, '#')
        ) {
            throw new InvalidArgumentException('Path endpoint reseller tidak valid.');
        }

        $request = Http::acceptJson()
            ->connectTimeout(max(1, (int) config('biatech-reseller.connect_timeout', 5)))
            ->timeout(max(2, (int) config('biatech-reseller.timeout', 15)))
            ->withOptions(['allow_redirects' => false]);

        if ($mode === 'bearer') {
            $request = $request->withToken($token);
        } elseif ($mode === 'header') {
            $header = (string) config('biatech-reseller.auth_header', 'X-API-Key');

            if (! preg_match('/^[A-Za-z0-9-]+$/', $header)) {
                throw new InvalidArgumentException('Nama header autentikasi reseller tidak valid.');
            }

            $request = $request->withHeaders([$header => $token]);
        } else {
            throw new InvalidArgumentException('Mode autentikasi reseller tidak dikenal.');
        }

        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

        $response = $request->get($url);

        if (! $response->successful()) {
            throw new RuntimeException('API reseller mengembalikan HTTP ' . $response->status() . '.');
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Respons API reseller bukan JSON object/array.');
        }

        return $payload;
    }
}
