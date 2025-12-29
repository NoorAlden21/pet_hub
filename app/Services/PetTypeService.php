<?php

namespace App\Services;

use App\Models\PetType;
use Illuminate\Support\Facades\DB;

class PetTypeService
{
    public function index(int $perPage = 15)
    {
        return PetType::with(['breeds'])->paginate($perPage);
    }

    public function create(array $data)
    {
        return PetType::create($data);

        // return PetType::create([
        //     'name_en' => $data['name_en'],
        //     'name_ar' => $data['name_ar'],
        // ]);
    }

    public function getDetails(PetType $petType)
    {
        return $petType->load(['breeds']);
    }

    public function update(PetType $petType, array $data)
    {
        $petType->update($data);
        // $petType->update([
        //     'name_en' => $data['name_en'],
        //     'name_ar' => $data['name_ar'],
        // ]);
        return $petType;
    }

    public function delete(PetType $petType)
    {
        $petType->delete();
    }
}
