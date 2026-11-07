<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class AdminActivityLogController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', AdminActivityLog::class);

        return response()->json(
            AdminActivityLog::query()->select(['id', 'user_id', 'action', 'subject_type', 'subject_id', 'created_at'])->latest('id')->paginate(20)
        );
    }

    public function show(AdminActivityLog $adminActivityLog): JsonResponse
    {
        Gate::authorize('view', $adminActivityLog);

        return response()->json($adminActivityLog->only(['id', 'user_id', 'action', 'subject_type', 'subject_id', 'created_at']));
    }
}
