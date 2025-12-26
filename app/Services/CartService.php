<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getUserCart(User $user)
    {
        return Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['total' => 0]
        )->load('items.product.coverImage');
    }

    public function addProduct(User $user, int $productId, int $quantity = 1)
    {
        return DB::transaction(function () use ($user, $productId, $quantity) {
            $product = Product::findOrFail($productId);

            $cart = $this->getUserCart($user);

            $item = $cart->items()->where('product_id', $productId)->first();
            if ($item) {
                $item->quantity += $quantity;
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
            }

            $this->recalculateTotal($cart);

            return $cart->load('items.product');
        });
    }

    public function updateItemQuantity(User $user, int $cartItemId, int $quantity)
    {
        return DB::transaction(function () use ($user, $cartItemId, $quantity) {
            $cart = $this->getUserCart($user);

            $item = $cart->items()->where('id', $cartItemId)->first();
            if (!$item) {
                throw new ModelNotFoundException('Cart item not found.');
            }

            if ($quantity <= 0) {
                $item->delete();
            } else {
                $item->quantity = $quantity;
                $item->save();
            }

            $this->recalculateTotal($cart);
            return $cart->load('items.product');
        });
    }

    public function removeItem(User $user, int $cartItemId)
    {
        return DB::transaction(function () use ($user, $cartItemId) {
            $cart = $this->getUserCart($user);

            $item = $cart->items()->where('id', $cartItemId)->first();

            if (!$item) {
                throw new ModelNotFoundException('Cart item not found.');
            }

            $item->delete();

            $this->recalculateTotal($cart);

            return $cart->load('items.product');
        });
    }

    public function clearCart(User $user): Cart
    {
        return DB::transaction(function () use ($user) {
            $cart = $this->getUserCart($user);

            $cart->items()->delete();

            $cart->total = 0;
            $cart->save();

            return $cart->load('items.product');
        });
    }

    protected function recalculateTotal(Cart $cart): void
    {
        $items = $cart->items()->with('product')->get();

        $total = $items->sum(function (CartItem $item) {
            $price = optional($item->product)->price ?? 0;

            return $price * $item->quantity;
        });

        $cart->update(['total' => $total]);
    }
}
