<?php

namespace App\Livewire\Biatech;

use App\Services\Storefront\CatalogService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;

class CatalogBrowser extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $provider = '';

    #[Url]
    public string $sort = 'recommended';

    public function clearFilters(): void
    {
        $this->reset(['query', 'category', 'provider']);
        $this->sort = 'recommended';
    }

    public function render(CatalogService $catalog): View
    {
        $products = collect($catalog->products())
            ->filter(fn (array $product): bool => $this->category === '' || $product['category'] === $this->category)
            ->filter(fn (array $product): bool => $this->provider === '' || $product['provider'] === $this->provider)
            ->filter(function (array $product): bool {
                $haystack = implode(' ', [
                    $product['name'], $product['category_name'], $product['provider'], $product['description'],
                    implode(' ', $product['countries']),
                ]);

                return trim($this->query) === '' || Str::contains(Str::lower($haystack), Str::lower(trim($this->query)));
            });

        $products = match ($this->sort) {
            'price_low' => $products->sortBy('price'),
            'price_high' => $products->sortByDesc('price'),
            'name' => $products->sortBy('name'),
            default => $products,
        };

        return view('livewire.biatech.catalog-browser', [
            'products' => $products->values()->all(),
            'categories' => $catalog->categories(),
            'providers' => $catalog->providers(),
        ]);
    }
}
