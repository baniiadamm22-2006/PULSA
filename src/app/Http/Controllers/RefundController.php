<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class RefundController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Refund::class);

        return response()->json(
            Refund::query()->select(['id', 'order_id', 'payment_id', 'amount', 'status', 'processed_at'])->latest('id')->paginate(20)
        );
    }

    public function show(Refund $refund): JsonResponse
    {
        Gate::authorize('view', $refund);

        return response()->json($refund->only(['id', 'order_id', 'payment_id', 'amount', 'status', 'processed_at']));
    }
}
