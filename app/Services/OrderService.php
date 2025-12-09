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
    public function indexFor(User $user, int $perPage = 15)
    {
        $query = Order::with(['items.product', 'user'])
            ->orderByDesc('created_at');

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate($perPage);
    }

    public function show(Order $order)
    {
        return $order->load(['items.product', 'user']);
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

                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => ["Product for cart item #{$item->id} no longer exists."],
                    ]);
                }

                if ($product->stock_quantity < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => [
                            "Not enough stock for product {$product->name}. " .
                                "Requested {$item->quantity}, available {$product->stock_quantity}."
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

            return $order->load(['items.product', 'user']);
        });
    }


    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order->fresh()->load(['items.product', 'user']);
    }

    public function cancel(Order $order): Order
    {
        if ($order->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => ['Only pending orders can be cancelled.'],
            ]);
        }

        $order->update(['status' => 'cancelled']);

        return $order->fresh()->load(['items.product', 'user']);
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }
}
