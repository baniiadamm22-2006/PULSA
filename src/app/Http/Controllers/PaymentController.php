<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Payment::class);

        return response()->json(
            Payment::query()->select(['id', 'order_id', 'gateway', 'gateway_reference', 'amount', 'status', 'created_at'])->latest('id')->paginate(20)
        );
    }

    public function show(Payment $payment): JsonResponse
    {
        Gate::authorize('view', $payment);

        return response()->json($payment->only(['id', 'order_id', 'gateway', 'gateway_reference', 'amount', 'status', 'created_at']));
    }
}
