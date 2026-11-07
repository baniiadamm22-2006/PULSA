<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Supplier::class);

        return response()->json(
            Supplier::query()->select(['id', 'name', 'code', 'is_active'])->latest('id')->paginate(20)
        );
    }

    public function show(Supplier $supplier): JsonResponse
    {
        Gate::authorize('view', $supplier);

        return response()->json($supplier->only(['id', 'name', 'code', 'is_active']));
    }
}
