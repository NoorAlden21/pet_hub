<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PetService
{
    public function indexFor(User $user, int $perPage = 15)
    {
        $query = Pet::query();
        if ($user->hasRole('admin')) {
            return $query->with(['petType', 'petBreed', 'owner'])->orderBy('id')->paginate($perPage);
        }

        return $query->adoptable()->paginate($perPage);
    }

    public function myPets(User $user, int $perPage = 15)
    {
        return Pet::ownedBy($user->id)->paginate($perPage);
    }

    public function create(User $user, array $data)
    {

        if ($user->hasRole('admin') && isset($data['owner_id'])) {
            $ownerId = $data['owner_id'];
        } else {
            $ownerId = $user->id;
            $data['is_adoptable'] = false;
        }

        $pet = Pet::create([
            'owner_id'     => $ownerId,
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
