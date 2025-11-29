<?php

namespace App\Services;

use App\Models\PetType;
use Illuminate\Support\Facades\DB;

class PetTypeService
{
    public function index(int $perPage = 15)
    {
        return PetType::with(['breeds'])->orderBy('id')->paginate($perPage);
    }

    public function create(array $data)
    {
        $petType = PetType::create([
            'name_en' => $data['name_en'],
            'name_ar' => $data['name_ar'],
        ]);

        return $petType;
    }

    public function update(PetType $petType, array $data)
    {
        $petType->update($data);
        return $petType;
    }

    public function delete(PetType $petType)
    {
        $petType->delete();
    }
}
