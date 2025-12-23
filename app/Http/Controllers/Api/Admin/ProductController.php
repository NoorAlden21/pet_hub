<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = $request->integer('per_page', 15);

        $product = $this->productService->indexFor($user, $perPage);

        return ProductResource::collection($product);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return response()->json([
            'message' => __('messages.product.created'),
            'product' => new ProductResource($product),
        ], 201);
    }

    public function show(Product $product)
    {
        $product = $this->productService->getDetails($product);
        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product = $this->productService->update($product, $request->validated());
        return response()->json([
            'message' => __('messages.product.updated'),
            'product' => new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return response()->json([
            'message' => __('messages.product.deleted'),
        ], 200);
    }
}
