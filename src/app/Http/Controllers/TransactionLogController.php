<?php

namespace App\Http\Controllers;

use App\Models\TransactionLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TransactionLogController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', TransactionLog::class);

        return response()->json(
            TransactionLog::query()->select(['id', 'transaction_id', 'event', 'status', 'created_at'])->latest('id')->paginate(20)
        );
    }

    public function show(TransactionLog $transactionLog): JsonResponse
    {
        Gate::authorize('view', $transactionLog);

        return response()->json($transactionLog->only(['id', 'transaction_id', 'event', 'status', 'created_at']));
    }
}
