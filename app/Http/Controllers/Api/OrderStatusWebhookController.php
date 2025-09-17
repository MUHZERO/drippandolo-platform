<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatusWebhookController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        // Simple token auth via header
        $token = $request->header('X-Webhook-Token');
        $expected = config('services.chatbot.webhook_token');

        if (empty($expected) || empty($token) || ! hash_equals((string) $expected, (string) $token)) {
            return response()->json([
                'ok' => false,
                'error' => 'unauthorized',
            ], 401);
        }

        $data = $request->validate([
            'query' => ['nullable', 'string', 'max:191'],
            'name' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:191'],
            'shopify_id' => ['nullable', 'string', 'max:191'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $limit = (int) ($data['limit'] ?? 10);
        $queryString = trim((string) ($data['query'] ?? ''));
        $name = trim((string) ($data['name'] ?? ''));
        $phone = trim((string) ($data['phone'] ?? ''));
        $shopifyId = trim((string) ($data['shopify_id'] ?? ''));

        // Normalize phone by stripping non-digits for robust matching
        $normalizedPhone = preg_replace('/\D+/', '', $phone);
        $normalizedQuery = preg_replace('/\D+/', '', $queryString);

        $orders = Order::query()
            ->when($name !== '', fn ($q) => $q->where('customer_name', 'like', "%{$name}%"))
            ->when($normalizedPhone !== '', function ($q) use ($normalizedPhone) {
                $normalizedCol = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone,' ',''),'-',''),'(',''),')',''),'+',''),'.','')";
                $q->whereRaw("$normalizedCol LIKE ?", ["%{$normalizedPhone}%"]);
            })
            // Explicit Shopify ID: exact match
            ->when($shopifyId !== '', fn ($q) => $q->where('shopify_order_id', $shopifyId))
            // Generic query: partial name, normalized phone partial, and partial Shopify ID
            ->when($queryString !== '', function ($q) use ($queryString, $normalizedQuery) {
                $normalizedCol = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone,' ',''),'-',''),'(',''),')',''),'+',''),'.','')";
                $q->where(function ($sub) use ($queryString, $normalizedQuery, $normalizedCol) {
                    $sub->where('customer_name', 'like', "%{$queryString}%")
                        ->orWhereRaw("$normalizedCol LIKE ?", ["%{$normalizedQuery}%"]) 
                        ->orWhere('shopify_order_id', 'like', "%{$queryString}%");
                });
            })
            ->latest()
            ->limit($limit)
            ->get([
                'id',
                'shopify_order_id',
                'customer_name',
                'customer_phone',
                'status',
                'tracking_number',
                'created_at',
                'updated_at',
            ]);

        $results = $orders->map(function (Order $order) {
            return [
                'id' => $order->id,
                'shopify_order_id' => $order->shopify_order_id,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'status' => $order->status,
                'status_label' => __("resources.statuses.{$order->status}") ?? $order->status,
                'tracking_number' => $order->tracking_number,
                'created_at' => optional($order->created_at)?->toIso8601String(),
                'updated_at' => optional($order->updated_at)?->toIso8601String(),
                'summary' => trim(sprintf(
                    '#%s | %s (%s) • %s%s',
                    $order->shopify_order_id ?: $order->id,
                    (string) $order->customer_name,
                    (string) $order->customer_phone,
                    __("resources.statuses.{$order->status}") ?? $order->status,
                    $order->tracking_number ? " • TRK: {$order->tracking_number}" : ''
                )),
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'count' => $results->count(),
            'results' => $results,
        ]);
    }
}
