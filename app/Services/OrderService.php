<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected UserNotificationsService $notificationService;
    public function __construct(UserNotificationsService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function indexFor(User $user, int $perPage = 15)
    {
        $query = Order::with(['items.product.coverImage', 'user'])
            ->orderByDesc('created_at');

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate($perPage);
    }

    public function show(Order $order)
    {
        return $order->load(['items.product.coverImage', 'user']);
    }

    public function placeOrderFromCart(User $user)
    {
        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new ModelNotFoundException('Cart is empty.');
        }

        return DB::transaction(function () use ($user, $cart) {
            $order = Order::create([
                'user_id'        => $user->id,
                'total'          => 0,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            $total = 0;

            foreach ($cart->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();  //lock

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => ["Product for cart item #{$item->id} no longer exists."],
                    ]);
                }

                if ($product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => [
                            "Not enough stock for product {$product->name}. Requested {$item->quantity}, available {$product->stock_quantity}."
                        ],
                    ]);
                }

                $price = $product->price;
                $lineTotal = $price * $item->quantity;

                $total += $lineTotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ]);

                $product->decrement('stock_quantity', $item->quantity);
            }

            $order->update(['total' => $total]);

            $cart->items()->delete();
            $cart->update(['total' => 0]);

            $this->notificationService->notifyAdmins(
                'order_placed',
                __('notifications.order_placed_title'),
                __('notifications.order_placed_body', [
                    'order_number' => $order->id,
                    'amount' => number_format((float)$order->total, 2, '.', ''),
                ]),
                ['order_id' => $order->id, 'status' => $order->status]
            );

            return $order->load(['items.product.coverImage', 'user']);
        });
    }


    public function updateStatus(Order $order, string $status, ?string $payment_status = null): Order
    {
        $order->update(['status' => $status]);

        if ($payment_status !== null) {
            $order->update(['payment_status' => $payment_status]);
        }

        $key = match ($status) {
            'confirmed'   => 'order_confirmed',
            'in_progress' => 'order_in_progress',
            'completed'   => 'order_completed',
            'cancelled'   => 'order_cancelled',
            default       => null,
        };

        if ($key) {
            $this->notificationService->notifyUser(
                $order->user,
                $key,
                __("notifications.{$key}_title"),
                __("notifications.{$key}_body", ['order_number' => $order->id]),
                ['order_id' => $order->id, 'status' => $status]
            );
        }

        return $order->fresh()->load(['items.product.coverImage', 'user']);
    }


    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order) {

            if ($order->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => ['Only pending orders can be cancelled.'],
                ]);
            }

            $order->update(['status' => 'cancelled']);

            $this->notificationService->notifyAdmins(
                'order_cancelled',
                __('notifications.order_cancelled_title'),
                __('notifications.order_cancelled_body', ['order_number' => $order->id]),
                ['order_id' => $order->id, 'status' => 'cancelled']
            );

            return $order->fresh()->load(['items.product.coverImage', 'user']);
        });
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }
}
