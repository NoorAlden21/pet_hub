<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;

class ProductCategoryService
{
    public function index(int $perPage = 15)
    {
        return ProductCategory::orderBy('id')->paginate($perPage);
    }

    public function create(array $data)
    {
        $productCategory = ProductCategory::create([
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar']
        ]);

        return $productCategory;
    }

    public function update(ProductCategory $productCategory, array $data)
    {
        $productCategory->update($data);
        return $productCategory;
    }

    public function delete(ProductCategory $productCategory)
    {
        $productCategory->delete();
    }
}
