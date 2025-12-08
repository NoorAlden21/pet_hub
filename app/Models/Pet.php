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


    //scopes

    public function scopeAdoptable($query)
    {
        return $query->where('is_adoptable', true);
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
