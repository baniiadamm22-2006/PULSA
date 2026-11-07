<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Setting::class);

        return response()->json(
            Setting::query()->select(['id', 'key', 'value', 'value_type', 'is_public'])->latest('id')->paginate(20)
        );
    }

    public function show(Setting $setting): JsonResponse
    {
        Gate::authorize('view', $setting);

        return response()->json($setting->only(['id', 'key', 'value', 'value_type', 'is_public']));
    }
}
