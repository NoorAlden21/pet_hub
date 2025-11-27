<?php

namespace App\Services;

use App\Models\PetBreed;
use Illuminate\Support\Facades\DB;

class PetBreedService
{
    public function index(int $perPage = 15)
    {
        return PetBreed::with('petType')->orderBy('id')->paginate($perPage);
    }

    public function create(array $data)
    {
        $petBreed = PetBreed::create([
            'pet_type_id' => $data['pet_type_id'],
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar'],
        ]);

        return $petBreed->load('petType');
    }

    public function update(PetBreed $petBreed, array $data)
    {
        $petBreed->update($data);
        return $petBreed->load('petType');
    }

    public function delete(PetBreed $petBreed)
    {
        $petBreed->delete();
    }
}
