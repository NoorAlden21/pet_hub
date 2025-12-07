<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CartItem\CartItemStoreRequest;
use App\Http\Requests\Admin\CartItem\CartItemUpdateRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{

    public function __construct(private CartService $cartService)
    {
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        $cart = $this->cartService->getUserCart($user);

        return response()->json([
            'data'    => new CartResource($cart),
        ]);
    }

    /**
     * POST /cart/items
     * body: { "product_id": 1, "quantity": 2 }
     */
    public function storeItem(CartItemStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $cart = $this->cartService->addProduct(
            $user,
            $validated['product_id'],
            $validated['quantity']
        );

        return response()->json([
            'message' => __('messages.cart_item.created'),
            'data'    => new CartResource($cart),
        ], 201);
    }

    /**
     * PUT /cart/items/{cartItem}
     * body: { "quantity": 3 }
     */
    public function updateItem(CartItemUpdateRequest $request, int $cartItemId): JsonResponse
    {
        $validated = $request->validated();

        $user = $request->user();

        $cart = $this->cartService->updateItemQuantity(
            $user,
            $cartItemId,
            $validated['quantity']
        );

        return response()->json([
            'message' => __('messages.cart_item.updated'),
            'data'    => new CartResource($cart),
        ]);
    }

    /**
     * DELETE /cart/items/{cartItem}
     */
    public function destroyItem(Request $request, int $cartItemId): JsonResponse
    {
        $user = $request->user();

        $cart = $this->cartService->removeItem($user, $cartItemId);

        return response()->json([
            'message' => __('messages.cart_item.deleted'),
            'data'    => new CartResource($cart),
        ]);
    }

    /**
     * DELETE /cart
     * Clear entire cart.
     */
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();

        $cart = $this->cartService->clearCart($user);

        return response()->json([
            'message' => __('messages.cart.cleared'),
            'data'    => new CartResource($cart),
        ]);
    }
}
