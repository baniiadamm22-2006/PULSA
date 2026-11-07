<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', Order::class);

        return response()->json(
            Order::query()->select(['id', 'invoice_number', 'total_amount', 'status', 'created_at'])->latest('id')->paginate(20)
        );
    }

    public function show(Order $order): JsonResponse
    {
        Gate::authorize('view', $order);

        return response()->json($order->only(['id', 'invoice_number', 'total_amount', 'status', 'created_at']));
    }
}
