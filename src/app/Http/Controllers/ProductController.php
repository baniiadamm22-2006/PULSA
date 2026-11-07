<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Product::class);

        return response()->json(
            Product::query()->select(['id', 'category_id', 'provider_id', 'code', 'name', 'description', 'cost_price', 'selling_price', 'is_active'])->latest('id')->paginate(20)
        );
    }

    public function show(Product $product): JsonResponse
    {
        Gate::authorize('view', $product);

        return response()->json($product->only(['id', 'category_id', 'provider_id', 'code', 'name', 'description', 'cost_price', 'selling_price', 'is_active']));
    }
}
