<?php

namespace App\Http\Controllers;

use App\Models\PaymentWebhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PaymentWebhookController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', PaymentWebhook::class);

        return response()->json(
            PaymentWebhook::query()->select(['id', 'payment_id', 'gateway', 'event_fingerprint', 'signature_valid', 'processed_at'])->latest('id')->paginate(20)
        );
    }

    public function show(PaymentWebhook $paymentWebhook): JsonResponse
    {
        Gate::authorize('view', $paymentWebhook);

        return response()->json($paymentWebhook->only(['id', 'payment_id', 'gateway', 'event_fingerprint', 'signature_valid', 'processed_at']));
    }
}
