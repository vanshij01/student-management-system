<?php

namespace App\Repositories;

use App\Contract\WardenRepositoryInterface;
use App\Models\Hostel;
use App\Models\User;
use App\Models\Warden;

class WardenRepository implements WardenRepositoryInterface
{
    public function getAll()
    {
        return Warden::orderBy("id", "desc")->get();
    }

    public function getById($id)
    {
        return Warden::with('user')->findOrFail($id);
    }

    public function create($params)
    {
        return Warden::create($params);
    }

    public function update($payLoad, $id)
    {
        $wardenUserId = $this->getWardenUserId($id);
        $user['email'] = $payLoad['email'];
        $user['name'] = $payLoad['first_name'] . ' ' . $payLoad['last_name'];
        $this->updateWardenEmail($user, $wardenUserId);

        $warden = Warden::findOrFail($id);
        return $warden->update($payLoad);
    }

    public function delete($id)
    {
        $user = Warden::findOrFail($id);
        return $user->delete();
    }

    public function wardenData()
    {
        return Warden::with('user')->orderBy("created_at", "desc")->get();
    }

    public function getWardenWithUser($id)
    {
        return Warden::with('user')->where("id", $id)->first();
    }

    public function isExist($name, $email, $number)
    {
        return Warden::where("name", $name)
            ->orWhere("email", $email)
            ->orWhere("number", $number)
            ->first();
    }

    public function checkEmailExists($params, $id)
    {
        $userId = $this->getById($id);
        $user = User::where('email', $params['email'])->where('id', '!=', $userId->user_id)->exists();
        return $user;
    }

    public function checkWardenEmailExists($params, $id)
    {
        $user = Warden::where('email', $params['email'])->where('id', '!=', $id)->exists();
        return $user;
    }

    public function getWardenUserId($id)
    {
        $wardenData = $this->getById($id);
        $userId = $wardenData->user_id;
        return $userId;
    }

    public function updateWardenEmail($user, $userId)
    {
        return User::where('id', $userId)->update([
            'email' => $user['email'],
            'name' => $user['name'],
        ]);
    }

    public function getHostelWarden($id)
    {
        return Hostel::where('warden_id', $id)->get();
    }
}
