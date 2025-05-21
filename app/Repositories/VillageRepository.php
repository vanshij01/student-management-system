<?php

namespace App\Repositories;

use App\Contract\VillageRepositoryInterface;
use App\Models\Village;

class VillageRepository implements VillageRepositoryInterface
{
    public function getAll()
    {
        return Village::orderBy("id", "desc")->get();
    }

    public function villageData()
    {
        return Village::orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return Village::findOrFail($id);
    }

    public function create($params)
    {
        return Village::create($params);
    }

    public function update($payLoad, $id)
    {
        $village = Village::findOrFail($id);
        return $village->update($payLoad);
    }

    public function delete($id)
    {
        $village = Village::findOrFail($id);
        return $village->delete();
    }
}
