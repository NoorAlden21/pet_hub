<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCategories\ProductCategoryStoreRequest;
use App\Http\Requests\Admin\ProductCategories\ProductCategoryUpdateRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;
use Throwable;

class ProductCategoryController extends Controller
{
    public function __construct(private ProductCategoryService $productCategoryService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $productCategory = $this->productCategoryService->index($perPage);

        return ProductCategoryResource::collection($productCategory);
    }

    public function store(ProductCategoryStoreRequest $request)
    {
        $productCategory = $this->productCategoryService->create($request->validated());
        return response()->json([
            'message' => __('messages.product_category.created'),
            'productCategory' => new ProductCategoryResource($productCategory),
        ], 201);
    }

    public function update(ProductCategoryUpdateRequest $request, ProductCategory $productCategory)
    {
        $productCategory = $this->productCategoryService->update($productCategory, $request->validated());
        return response()->json([
            'message' => __('messages.product_category.updated'),
            'ProductCategory' => new ProductCategoryResource($productCategory)
        ], 200);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $this->productCategoryService->delete($productCategory);
        return response()->json([
            'message' => __('messages.product_category.deleted'),
            'ProductCategory' => new ProductCategoryResource($productCategory)
        ], 200);
    }
}
