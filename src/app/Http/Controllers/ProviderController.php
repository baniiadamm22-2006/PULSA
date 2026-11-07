<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ProviderController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Provider::class);

        return response()->json(
            Provider::query()->select(['id', 'name', 'code', 'logo', 'is_active'])->latest('id')->paginate(20)
        );
    }

    public function show(Provider $provider): JsonResponse
    {
        Gate::authorize('view', $provider);

        return response()->json($provider->only(['id', 'name', 'code', 'logo', 'is_active']));
    }
}
