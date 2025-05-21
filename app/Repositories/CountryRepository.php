<?php

namespace App\Repositories;

use App\Contract\CountryRepositoryInterface;
use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAll()
    {
        return Country::orderBy("id")->get();
    }

    public function getById($id)
    {
        return Country::findOrFail($id);
    }

    public function create($params)
    {
        return Country::create($params);
    }

    public function update($payLoad, $id)
    {
        $country = Country::findOrFail($id);
        return $country->update($payLoad);
    }

    public function delete($id)
    {
        $country = Country::findOrFail($id);
        return $country->delete();
    }
}
