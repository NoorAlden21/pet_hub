<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['owner_id', 'pet_type_id', 'pet_breed_id',  'name', 'date_of_birth', 'gender', 'description', 'is_adoptable'];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_adoptable'  => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }
    public function petBreed()
    {
        return $this->belongsTo(PetBreed::class);
    }

    public function images()
    {
        return $this->hasMany(PetImage::class)->orderBy('id');
    }

    public function coverImage()
    {
        // the first inserted image (smallest id) for this pet
        return $this->hasOne(PetImage::class)->ofMany('id', 'min');
    }

    public function adoptionApplication()
    {
        return $this->hasMany(AdoptionApplication::class);
    }


    //scopes

    public function scopeAdoptable($query)
    {
        return $query->where('is_adoptable', true);
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('owner_id', $userId);
    }
}
