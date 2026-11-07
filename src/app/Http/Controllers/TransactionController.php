<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Transaction::class);

        return response()->json(
            Transaction::query()->select(['id', 'order_id', 'local_reference', 'supplier_reference', 'status', 'created_at'])->latest('id')->paginate(20)
        );
    }

    public function show(Transaction $transaction): JsonResponse
    {
        Gate::authorize('view', $transaction);

        return response()->json($transaction->only(['id', 'order_id', 'local_reference', 'supplier_reference', 'status', 'created_at']));
    }
}
