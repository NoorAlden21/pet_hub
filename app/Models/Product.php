<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_category_id', 'name_en', 'name_ar', 'description', 'price', 'stock_quantity', 'is_active'];
    protected $appends = ['name'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];


    public function getNameAttribute()
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'ar' => $this->name_ar ?? $this->name_en,
            default => $this->name_en ?? $this->name_ar
        };
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
