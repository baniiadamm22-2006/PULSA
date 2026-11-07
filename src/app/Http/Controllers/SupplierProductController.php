<?php

namespace App\Http\Controllers;

use App\Models\SupplierProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SupplierProductController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', SupplierProduct::class);

        return response()->json(
            SupplierProduct::query()->select(['id', 'supplier_id', 'product_id', 'supplier_product_code', 'supplier_price', 'is_active'])->latest('id')->paginate(20)
        );
    }

    public function show(SupplierProduct $supplierProduct): JsonResponse
    {
        Gate::authorize('view', $supplierProduct);

        return response()->json($supplierProduct->only(['id', 'supplier_id', 'product_id', 'supplier_product_code', 'supplier_price', 'is_active']));
    }
}
