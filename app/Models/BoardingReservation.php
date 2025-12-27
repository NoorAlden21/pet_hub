<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingReservation extends Model
{
    protected $fillable = [
        'user_id',
        'pet_type_id', 'pet_breed_id',
        'age_months',
        'start_at', 'end_at',
        'billable_hours',
        'status',
        'total',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }
    public function petBreed()
    {
        return $this->belongsTo(PetBreed::class);
    }

    public function services()
    {
        return $this->belongsToMany(BoardingService::class, 'boarding_reservation_services')
            ->withPivot(['quantity'])
            ->withTimestamps();
    }
}
