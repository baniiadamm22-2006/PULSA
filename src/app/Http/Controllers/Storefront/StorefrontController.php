<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Storefront\CatalogService;
use Illuminate\Contracts\View\View;

class StorefrontController extends Controller
{
    public function __construct(private readonly CatalogService $catalog)
    {
    }

    public function home(): View
    {
        return view('storefront.home', [
            'categories' => $this->catalog->categories(),
            'featured' => $this->catalog->featured(),
        ]);
    }

    public function catalog(): View
    {
        return view('storefront.catalog');
    }

    public function product(string $slug): View
    {
        $product = $this->catalog->findProduct($slug);
        abort_if($product === null, 404);

        $related = array_values(array_filter(
            $this->catalog->products(),
            fn (array $item): bool => $item['category'] === $product['category']
                && $item['slug'] !== $slug,
        ));

        return view('storefront.product', compact('product', 'related'));
    }

    public function checkout(string $slug): View
    {
        $product = $this->catalog->findProduct($slug);
        abort_if($product === null, 404);

        return view('storefront.checkout', compact('product'));
    }

    public function payment(string $invoice): never
    {
        abort(404);
    }

    public function order(string $invoice): never
    {
        abort(404);
    }

    public function track(): View
    {
        return view('storefront.track');
    }

    public function help(): View
    {
        return view('storefront.help');
    }
}
