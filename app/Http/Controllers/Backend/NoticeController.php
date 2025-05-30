<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\NoticeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    private $noticeRepository;

    public function __construct(NoticeRepository $noticeRepository)
    {
        $this->noticeRepository = $noticeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.notice.index');
    }

    public function noticeData(Request $request)
    {
        $notice = $this->noticeRepository->getAll();
        return datatables()->of($notice)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['is_visible_for_student'] = ($request->is_visible_for_student && $params['is_visible_for_student'] == 'on') ? true : false;
        $params['created_by'] = Auth::user()->id;

        $this->noticeRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Notice added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notice = $this->noticeRepository->getById($id);
        return view('backend.notice.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $notice = $this->noticeRepository->getById($id);
        return view('backend.notice.update', compact('notice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $payLoad['is_visible_for_student'] = ($request->is_visible_for_student && $payLoad['is_visible_for_student'] == 'on') ? true : false;
        $this->noticeRepository->update($payLoad, $id);
        return redirect('notices')->with('success', 'Notice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->noticeRepository->delete($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Notice deleted successfully',
        ]);
    }
}
