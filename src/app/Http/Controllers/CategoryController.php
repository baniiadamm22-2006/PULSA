<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Category::class);

        return response()->json(
            Category::query()->select(['id', 'name', 'slug', 'description', 'sort_order', 'is_active'])->latest('id')->paginate(20)
        );
    }

    public function show(Category $category): JsonResponse
    {
        Gate::authorize('view', $category);

        return response()->json($category->only(['id', 'name', 'slug', 'description', 'sort_order', 'is_active']));
    }
}
