<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingService extends Model
{
    protected $fillable = ['name_en', 'name_ar', 'price', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') {
            return $this->name_ar;
        }
        return $this->name_en;
    }
}
