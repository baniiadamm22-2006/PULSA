<?php

declare(strict_types=1);

namespace App\Services\Storefront;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CatalogService
{
    /** @return array<int, array<string, mixed>> */
    public function categories(): array
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => [
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description ?: 'Informasi produk digital BIATECH.',
                'icon' => $this->categoryIcon($category->slug),
                'tone' => $this->categoryTone($category->slug),
            ])->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function products(): array
    {
        return Product::query()
            ->with(['category', 'provider'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('id')
            ->get()
            ->map(fn (Product $product): array => $this->mapDatabaseProduct($product))
            ->all();
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
        return array_slice($this->products(), 0, max(0, $limit));
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

        return [
            'slug' => Str::slug($product->name) . '-' . $product->id,
            'name' => $product->name,
            'category' => $categorySlug,
            'category_name' => $product->category?->name ?? 'Produk Digital',
            'provider' => $product->provider?->name ?? 'Provider',
            'provider_code' => $product->provider?->code ?? '-',
            'price' => (int) round((float) $product->selling_price),
            'description' => $product->description ?: 'Produk digital BIATECH.',
            'quota' => $product->quota ?: null,
            'validity_days' => $product->validity_days,
            'countries' => is_array($countries) ? $countries : [],
            'terms' => $product->terms ?: 'Pastikan informasi produk sesuai kebutuhan.',
            'tone' => $this->categoryTone($categorySlug),
            'icon' => $this->categoryIcon($categorySlug),
        ];
    }
}
