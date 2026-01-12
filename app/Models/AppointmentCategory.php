<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentCategory extends Model
{
    protected $fillable = ['name_en', 'name_ar', 'is_active'];

    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'ar' => $this->name_ar ?? $this->name_en,
            default => $this->name_en ?? $this->name_ar,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
