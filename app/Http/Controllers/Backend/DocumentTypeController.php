<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Repositories\DocumentTypeRepository;
use App\Repositories\StudentDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class DocumentTypeController extends Controller
{
    private $documentTypeRepository;
    private $studentDdocumentRepository;

    public function __construct(
        DocumentTypeRepository $documentTypeRepository,
        StudentDocumentRepository $studentDdocumentRepository,
        )
    {
        $this->documentTypeRepository = $documentTypeRepository;
        $this->studentDdocumentRepository = $studentDdocumentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.document_type.index');
    }

    public function documentTypeData()
    {
        $document_type = $this->documentTypeRepository->getAll();
        return datatables()->of($document_type)->addIndexColumn()->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.document_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $this->documentTypeRepository->create($params);
        return response()->json([
            'status' => 'success',
            'message' => 'Document Type added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document_type = $this->documentTypeRepository->getById($id);
        return view('backend.document_type.show', compact('document_type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $document_type = $this->documentTypeRepository->getById($id);
        return view('backend.document_type.update', compact('document_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        $this->documentTypeRepository->update($payLoad, $id);
        return redirect('document_type')->with([
            'status' => 'success',
            'message' => 'Document Type updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /* $isVillageExists = $this->studentDdocumentRepository->isStudentDocumentExist($id);
        if ($isVillageExists) {
            return response()->json([
                "status" => false,
                "message" => "This is already in Use"
            ]);
        } */

        $this->documentTypeRepository->delete($id);
        Session::flash('success', 'DocumentType deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Document Type deleted successfully!',
        ]);
    }
}
