<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetBreed extends Model
{
    protected $fillable = ['pet_type_id', 'name_en', 'name_ar'];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'ar' => $this->name_ar ?? $this->name_en,
            default => $this->name_en ?? $this->name_ar
        };
    }

    public function type()
    {
        return $this->belongsTo(PetType::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }
}
