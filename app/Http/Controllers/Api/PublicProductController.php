<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $products = $this->productService->publicIndex($perPage);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return new ProductResource($this->productService->getDetails($product));
    }
}
