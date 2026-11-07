<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrderItemController extends Controller
{
    // Belum didaftarkan pada routes; Filament menangani operasi admin.
    // Policy wajib dibuat sebelum controller ini dibuka sebagai endpoint.
    public function index(): JsonResponse
    {
        Gate::authorize('viewAny', OrderItem::class);

        return response()->json(
            OrderItem::query()->select(['id', 'order_id', 'product_name_snapshot', 'quantity', 'total_amount'])->latest('id')->paginate(20)
        );
    }

    public function show(OrderItem $orderItem): JsonResponse
    {
        Gate::authorize('view', $orderItem);

        return response()->json($orderItem->only(['id', 'order_id', 'product_name_snapshot', 'quantity', 'total_amount']));
    }
}
