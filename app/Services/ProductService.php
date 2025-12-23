<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function indexFor(User $user)
    {
        $query = Product::query()->with(['productCategory']);

        if ($user->hasRole('admin')) {
            return $query->paginate(15);
        }

        return $query->active()->paginate(15);
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
