<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['owner_id', 'breed_id', 'name', 'date_of_birth', 'gender', 'description', 'is_adoptable'];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_adoptable'  => 'boolean',
    ];


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'breed_id');
    }
}
