<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function indexFor(User $user)
    {
        $query = Product::query()->with(['productCategory', 'petType', 'coverImage']);

        if ($user->hasRole('admin')) {
            return $query->paginate(15);
        }

        return $query->active()->paginate(15);
    }

    public function publicIndex(int $perPage = 15)
    {
        return Product::query()
            ->with(['productCategory', 'petType', 'coverImage'])
            ->active()
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function create(array $data, array $images = [])
    {
        return DB::transaction(function () use ($data, $images) {
            unset($data['images']);
            $product = Product::create($data);
            $this->storeImages($product, $images);
            return $product->load(['productCategory', 'petType', 'coverImage', 'images']);
        });
    }

    public function update(Product $product, array $data, array $images = [])
    {
        return DB::transaction(function () use ($product, $data, $images) {

            unset($data['images']);

            $product->update($data);

            $this->storeImages($product, $images);

            return $product->load(['productCategory', 'petType', 'coverImage', 'images']);
        });
    }

    private function storeImages(Product $product, array $images): void
    {
        foreach ($images as $file) {
            $path = $file->store('pictures/products', 'public');
            $url  = Storage::disk('public')->url($path);

            $product->images()->create([
                'path' => $path,
                'url'  => $url,
            ]);
        }
    }

    public function getDetails(Product $product)
    {
        return $product->load(['productCategory', 'petType', 'coverImage', 'images']);
    }

    public function delete(Product $product)
    {
        $product->delete();
    }
}
