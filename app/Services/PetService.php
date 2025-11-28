<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Facades\DB;

class PetService
{
    public function index(int $perPage = 15)
    {
        return Pet::with(['petType', 'petBreed', 'owner'])->orderBy('id')->paginate($perPage);
    }

    public function create(array $data)
    {
        $pet = Pet::create([
            'owner_id' => $data['owner_id'],
            'pet_type_id' => $data['pet_type_id'],
            'pet_breed_id' => $data['pet_breed_id'],
            'name' => $data['name'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'description' => $data['description'],
            'is_adoptable' => $data['is_adoptable'],
        ]);

        return $pet->load(['petType', 'petBreed', 'owner']);
    }

    public function update(Pet $pet, array $data)
    {
        $pet->update($data);
        return $pet->load(['petType', 'petBreed', 'owner']);
    }

    public function delete(Pet $pet)
    {
        $pet->delete();
    }
}
