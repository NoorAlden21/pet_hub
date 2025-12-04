<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name_en', 'name_ar'];
    protected $appends = ['name'];


    public function getNameAttribute()
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'ar' => $this->name_ar ?? $this->name_en,
            default => $this->name_en ?? $this->name_ar
        };
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
