<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetService
{
    public function indexFor(User $user, int $perPage = 15)
    {
        $query = Pet::query();
        if ($user->hasRole('admin')) {
            return $query->with(['petType', 'petBreed', 'owner', 'coverImage'])->orderBy('id')->paginate($perPage);
        }

        return $query->adoptable()->paginate($perPage);
    }

    public function publicIndex(int $perPage = 15)
    {
        return Pet::query()
            ->adoptable()
            ->with(['petType', 'petBreed', 'coverImage'])
            ->orderBy('id')
            ->paginate($perPage);
    }


    public function myPets(User $user, int $perPage = 15)
    {
        return Pet::ownedBy($user->id)->paginate($perPage);
    }

    public function create(User $user, array $data, array $images = [])
    {
        return DB::transaction(function () use ($user, $data, $images) {
            if ($user->hasRole('admin') && isset($data['owner_id'])) {
                $ownerId = $data['owner_id'];
            } else {
                $ownerId = $user->id;
                $data['is_adoptable'] = false;
            }

            $pet = Pet::create([
                'owner_id'      => $ownerId,
                'pet_type_id'   => $data['pet_type_id'],
                'pet_breed_id'  => $data['pet_breed_id'],
                'name'          => $data['name'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender'        => $data['gender'],
                'description'   => $data['description'] ?? null,
                'is_adoptable'  => $data['is_adoptable'] ?? false,
            ]);

            $this->storeImages($pet, $images);

            return $pet->load(['petType', 'petBreed', 'owner', 'coverImage', 'images']);
        });
    }

    public function update(Pet $pet, array $data, array $images = [])
    {
        return DB::transaction(function () use ($pet, $data, $images) {

            unset($data['images']);

            $pet->update($data);

            $this->storeImages($pet, $images);

            return $pet->load(['petType', 'petBreed', 'owner', 'coverImage', 'images']);
        });
    }

    private function storeImages(Pet $pet, array $images): void
    {
        foreach ($images as $file) {
            $path = $file->store('pictures/pets', 'public');
            $url  = Storage::disk('public')->url($path);

            $pet->images()->create([
                'path' => $path,
                'url'  => $url,
            ]);
        }
    }

    public function getDetails(Pet $pet)
    {
        return $pet->load(['petType', 'petBreed', 'owner', 'coverImage', 'images']);
    }

    public function delete(Pet $pet)
    {
        $pet->delete();
    }
}
