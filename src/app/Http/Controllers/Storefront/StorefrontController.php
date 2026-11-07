<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\Storefront\CatalogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function __construct(private readonly CatalogService $catalog) {}

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
            fn (array $item): bool => $item['category'] === $product['category'] && $item['slug'] !== $slug
        ));

        return view('storefront.product', compact('product', 'related'));
    }

    public function checkout(string $slug): View
    {
        $product = $this->catalog->findProduct($slug);
        abort_if($product === null, 404);

        return view('storefront.checkout', compact('product'));
    }

    public function payment(Request $request, string $invoice): View
    {
        $order = $request->session()->get('biatech_demo_orders.'.$invoice);
        abort_unless(is_array($order), 404);

        return view('storefront.payment', compact('order', 'invoice'));
    }

    public function order(Request $request, string $invoice): View
    {
        $order = $request->session()->get('biatech_demo_orders.'.$invoice);
        abort_unless(is_array($order), 404);

        return view('storefront.order', compact('order', 'invoice'));
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
