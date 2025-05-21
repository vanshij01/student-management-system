<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Repositories\EventsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    private $eventRepository;

    public function __construct(EventsRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Backend.events.index');
    }

    public function eventData()
    {
        $event = $this->eventRepository->getAll();
        return datatables()->of($event)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'name' => ['required', 'regex:/^\d*[a-zA-Z]{1,}\d*/', 'max:50'],
            'start_datetime' => ['required'],
            'end_datetime' => ['required'],
        ], [
            'name' => 'The event name field is required.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $params['start_datetime'] = \DateTime::createFromFormat('d/m/Y H:i', $params['start_datetime'])->format('Y-m-d H:i:s');
        $params['end_datetime'] = \DateTime::createFromFormat('d/m/Y H:i', $params['end_datetime'])->format('Y-m-d H:i:s');
        $params['created_by'] = Auth::user()->id;
        $this->eventRepository->create($params);
        return Redirect::route('event.index')->with([
            'status' => 'success',
            'message' => 'Event added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = $this->eventRepository->getById($id);
        return view('backend.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = $this->eventRepository->getById($id);
        return view('Backend.events.update', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payload = $request->all();
        $validator = Validator::make($payload, [
            'name' => ['required', 'regex:/^\d*[a-zA-Z]{1,}\d*/', 'max:50'],
            'start_datetime' => ['required'],
            'end_datetime' => ['required'],
        ], [
            'name' => 'The event name field is required.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payload);
        }

        $payload['start_datetime'] = \DateTime::createFromFormat('d/m/Y H:i', $payload['start_datetime'])->format('Y-m-d H:i:s');
        $payload['end_datetime'] = \DateTime::createFromFormat('d/m/Y H:i', $payload['end_datetime'])->format('Y-m-d H:i:s');
        $payload['created_by'] = Auth::user()->id;
        $this->eventRepository->Update($payload, $id);
        return Redirect::route('event.index')->with([
            'status' => 'success',
            'message' => 'Event updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->eventRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Event Deleted successfully',
        ]);
    }
}
