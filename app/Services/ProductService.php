<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function index(int $perPage = 15)
    {
        return Product::with(['productCategory'])->orderBy('id')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Product::create($data)->load(['productCategory']);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
