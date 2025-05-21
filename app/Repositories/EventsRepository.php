<?php

namespace App\Repositories;

use App\Models\Events;
use App\Contract\EventsRepositoryInterface;

class EventsRepository implements EventsRepositoryInterface
{
    public function getAll()
    {
        return Events::orderBy("id", "desc")->with('user')->get();
    }

    public function eventData()
    {
        return Events::orderBy("id", "desc")->with('user')->get();
    }

    public function getById($id)
    {
        return Events::findOrFail($id);
    }

    public function create($params)
    {
        return Events::create($params);
    }

    public function update($payLoad, $id)
    {
        $event = Events::findOrFail($id);
        $event->update($payLoad);
        return $event;
    }

    public function delete($id)
    {
        return Events::findOrFail($id)->delete();
    }

    public function getUpcoming()
    {
        return Events::where('start_datetime', '>', now())
            ->orderBy('start_datetime', 'asc')
            ->take(3)
            ->get();
    }
}
