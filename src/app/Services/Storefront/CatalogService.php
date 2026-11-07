<?php

namespace App\Services\Storefront;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CatalogService
{
    public function isDemo(): bool
    {
        return config('biatech-storefront.catalog_source', 'demo') === 'demo';
    }

    /** @return array<int, array<string, mixed>> */
    public function categories(): array
    {
        if (! $this->isDemo()) {
            return Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(fn (Category $category): array => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description ?: 'Temukan produk digital sesuai kebutuhan.',
                    'icon' => $this->categoryIcon($category->slug),
                    'tone' => $this->categoryTone($category->slug),
                ])->all();
        }

        return [
            ['name' => 'Pulsa Reguler', 'slug' => 'pulsa-reguler', 'description' => 'Isi pulsa berbagai operator.', 'icon' => 'smartphone', 'tone' => 'orange'],
            ['name' => 'Paket Internet', 'slug' => 'paket-data-internet', 'description' => 'Kuota internet sehari-hari.', 'icon' => 'wifi', 'tone' => 'blue'],
            ['name' => 'Roaming Internasional', 'slug' => 'roaming-internasional', 'description' => 'Tetap terhubung di luar negeri.', 'icon' => 'globe', 'tone' => 'violet'],
            ['name' => 'Roaming Umroh', 'slug' => 'roaming-umroh', 'description' => 'Konektivitas perjalanan ibadah.', 'icon' => 'plane', 'tone' => 'mint'],
            ['name' => 'Token Listrik PLN', 'slug' => 'token-listrik-pln', 'description' => 'Beli token listrik prabayar.', 'icon' => 'zap', 'tone' => 'yellow'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function products(): array
    {
        if (! $this->isDemo()) {
            return Product::query()
                ->with(['category', 'provider'])
                ->where('is_active', true)
                ->whereHas('category', fn ($query) => $query->where('is_active', true))
                ->orderBy('id', 'desc')
                ->get()
                ->map(fn (Product $product): array => $this->mapDatabaseProduct($product))
                ->all();
        }

        return [
            $this->demo('pulsa-telkomsel-25', 'Pulsa Telkomsel 25.000', 'pulsa-reguler', 'Telkomsel', 'TSEL', 27000, 'Pulsa reguler untuk nomor Telkomsel.', 'Rp25.000 pulsa', null, 'orange'),
            $this->demo('pulsa-xl-50', 'Pulsa XL 50.000', 'pulsa-reguler', 'XL', 'XL', 52000, 'Pulsa reguler untuk nomor XL.', 'Rp50.000 pulsa', null, 'blue'),
            $this->demo('pulsa-indosat-100', 'Pulsa Indosat 100.000', 'pulsa-reguler', 'Indosat', 'ISAT', 102500, 'Pulsa reguler untuk nomor Indosat.', 'Rp100.000 pulsa', null, 'violet'),
            $this->demo('data-telkomsel-10gb', 'Paket Data Telkomsel 10 GB', 'paket-data-internet', 'Telkomsel', 'TSEL', 39000, 'Paket internet untuk kebutuhan harian.', '10 GB', 30, 'blue'),
            $this->demo('data-xl-15gb', 'Paket Internet XL 15 GB', 'paket-data-internet', 'XL', 'XL', 49000, 'Kuota internet pilihan untuk aktivitas digital.', '15 GB', 30, 'mint'),
            $this->demo('data-tri-5gb', 'Paket Internet Tri 5 GB', 'paket-data-internet', 'Tri', 'TRI', 22000, 'Paket internet untuk kebutuhan dasar.', '5 GB', 7, 'orange'),
            $this->demo('roaming-asia-3gb', 'Roaming Asia 3 GB', 'roaming-internasional', 'Roaming', 'ROAM', 89000, 'Paket data perjalanan lintas negara Asia.', '3 GB', 7, 'violet', ['Malaysia', 'Singapura', 'Thailand']),
            $this->demo('roaming-jepang-5gb', 'Roaming Jepang 5 GB', 'roaming-internasional', 'Roaming', 'ROAM', 145000, 'Tetap online selama perjalanan ke Jepang.', '5 GB', 10, 'blue', ['Jepang']),
            $this->demo('roaming-eropa-5gb', 'Roaming Eropa 5 GB', 'roaming-internasional', 'Roaming', 'ROAM', 199000, 'Paket internet untuk perjalanan ke Eropa.', '5 GB', 10, 'mint', ['Prancis', 'Jerman', 'Italia']),
            $this->demo('umroh-5gb', 'Paket Umroh 5 GB', 'roaming-umroh', 'Roaming', 'ROAM', 99000, 'Paket roaming untuk perjalanan Umroh.', '5 GB', 10, 'mint', ['Arab Saudi']),
            $this->demo('umroh-10gb', 'Paket Umroh 10 GB', 'roaming-umroh', 'Roaming', 'ROAM', 159000, 'Paket roaming Umroh dengan kuota lebih besar.', '10 GB', 15, 'orange', ['Arab Saudi']),
            $this->demo('umroh-20gb', 'Paket Umroh 20 GB', 'roaming-umroh', 'Roaming', 'ROAM', 249000, 'Konektivitas perjalanan Umroh lebih lama.', '20 GB', 30, 'violet', ['Arab Saudi']),
            $this->demo('pln-20', 'Token Listrik PLN 20.000', 'token-listrik-pln', 'PLN', 'PLN', 22000, 'Pembelian token listrik prabayar.', 'Rp20.000 token', null, 'yellow'),
            $this->demo('pln-50', 'Token Listrik PLN 50.000', 'token-listrik-pln', 'PLN', 'PLN', 52000, 'Token untuk meter listrik prabayar.', 'Rp50.000 token', null, 'orange'),
            $this->demo('pln-100', 'Token Listrik PLN 100.000', 'token-listrik-pln', 'PLN', 'PLN', 102500, 'Token listrik prabayar nominal besar.', 'Rp100.000 token', null, 'blue'),
        ];
    }

    /** @return array<string, mixed>|null */
    public function findProduct(string $slug): ?array
    {
        foreach ($this->products() as $product) {
            if ($product['slug'] === $slug) {
                return $product;
            }
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    public function featured(int $limit = 8): array
    {
        // Mix categories for the homepage instead of showing only one provider.
        $products = $this->products();
        $slugs = ['data-telkomsel-10gb', 'pulsa-telkomsel-25', 'umroh-10gb', 'pln-50', 'roaming-asia-3gb', 'data-xl-15gb', 'pulsa-xl-50', 'pln-100'];

        if (! $this->isDemo()) {
            return array_slice($products, 0, $limit);
        }

        $ordered = [];
        foreach ($slugs as $slug) {
            foreach ($products as $product) {
                if ($product['slug'] === $slug) {
                    $ordered[] = $product;
                    break;
                }
            }
        }

        return array_slice($ordered, 0, $limit);
    }

    /** @return array<int, string> */
    public function providers(): array
    {
        $providers = array_values(array_unique(array_column($this->products(), 'provider')));
        sort($providers);

        return $providers;
    }

    public function categoryIcon(string $slug): string
    {
        return match (true) {
            str_contains($slug, 'umroh') => 'plane',
            str_contains($slug, 'roaming') => 'globe',
            str_contains($slug, 'pulsa') => 'smartphone',
            str_contains($slug, 'internet'), str_contains($slug, 'data') => 'wifi',
            str_contains($slug, 'token'), str_contains($slug, 'listrik') => 'zap',
            default => 'grid',
        };
    }

    public function categoryTone(string $slug): string
    {
        return match ($this->categoryIcon($slug)) {
            'smartphone' => 'orange',
            'wifi' => 'blue',
            'globe' => 'violet',
            'plane' => 'mint',
            'zap' => 'yellow',
            default => 'blue',
        };
    }

    /** @return array<string, mixed> */
    private function mapDatabaseProduct(Product $product): array
    {
        $categorySlug = $product->category?->slug ?? '';
        $countries = $product->countries ?? [];
        if (! is_array($countries)) {
            $countries = [];
        }

        return [
            'slug' => Str::slug($product->name).'-'.$product->id,
            'name' => $product->name,
            'category' => $categorySlug,
            'category_name' => $product->category?->name ?? 'Produk Digital',
            'provider' => $product->provider?->name ?? 'Provider',
            'provider_code' => $product->provider?->code ?? '-',
            'price' => (int) round((float) $product->selling_price),
            'description' => $product->description ?: 'Produk digital BIATECH.',
            'quota' => $product->quota ?: null,
            'validity_days' => $product->validity_days,
            'countries' => $countries,
            'terms' => $product->terms ?: 'Pastikan nomor tujuan sesuai sebelum melanjutkan.',
            'tone' => $this->categoryTone($categorySlug),
            'icon' => $this->categoryIcon($categorySlug),
        ];
    }

    /** @param array<int, string> $countries
     *  @return array<string, mixed>
     */
    private function demo(string $slug, string $name, string $category, string $provider, string $code, int $price, string $description, string $quota, ?int $days, string $tone, array $countries = []): array
    {
        return [
            'slug' => $slug,
            'name' => $name,
            'category' => $category,
            'category_name' => match ($category) {
                'pulsa-reguler' => 'Pulsa Reguler',
                'paket-data-internet' => 'Paket Internet',
                'roaming-internasional' => 'Roaming Internasional',
                'roaming-umroh' => 'Roaming Umroh',
                default => 'Token Listrik PLN',
            },
            'provider' => $provider,
            'provider_code' => $code,
            'price' => $price,
            'description' => $description,
            'quota' => $quota,
            'validity_days' => $days,
            'countries' => $countries,
            'terms' => 'Contoh produk untuk demonstrasi antarmuka. Harga dan ketersediaan bukan penawaran transaksi nyata.',
            'tone' => $tone,
            'icon' => $this->categoryIcon($category),
        ];
    }
}
