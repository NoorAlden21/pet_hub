<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'pet_type_id',
        'pet_breed_id',
        'appointment_category_id',
        'appointment_date',
        'notes',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
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

    public function category()
    {
        return $this->belongsTo(AppointmentCategory::class, 'appointment_category_id');
    }
}
