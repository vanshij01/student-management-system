@extends('backend.layouts.app')
@section('title', 'Admission Listing')
@section('styles')
    <style>
        div.dataTables_length {
            margin-left: -68px;
        }

        div.dataTables_filter {
            flex: 1;
        }

        .dataTables_wrapper .dataTables_filter label {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            min-height: 50px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
            width: 100%;
        }

        .paginate-info {
            font-size: 1rem;
            color: #000;
            font-weight: 500;
        }

        .responsive-table nav {
            background: none;
            height: 0;
        }

        /* .dataTables_scrollBody {
                                                                                                                                overflow-x: scroll !important;
                                                                                                                                overflow-y: hidden !important;
                                                                                                                                position: static !important;
                                                                                                                            } */

        .pagination .page-item {
            display: flex;
            align-items: center;
            padding-right: 10px;
        }

        .d-none {
            display: none;
        }

        .page-length-list {
            position: absolute;
            top: 85px;
            left: 0;
        }

        .sk-primary {
            position: absolute;
            left: 45%;
            top: 50%;
        }

        .card-datatable {
            min-height: 200px;
            /* overflow: hidden; */
        }

        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-button,
        input[type="search"]::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        select#selPagesUp {
            /* position: absolute; */
            width: auto;
            top: 0px;
            z-index: 9;
        }

        select#selPagesBottom {
            position: relative;
            width: auto;
            /* top: 115px; */
            z-index: 9;
        }

        .disabled>.page-link {
            background-color: transparent;
        }

        .filter-card

        /* .card-body */
            {
            background: #A29678;
        }

        .form-floating-outline label::after .filter_label {
            background: #A29678;
        }

        .find_address {
            width: 100%;
            height: 46px;
        }

        select#selPagesUp {
            margin: 0 10px;
            min-height: 50px;
            width: 100px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
            padding: 4px;
            top: 20px;
        }

        .page_wrapper {
            display: flex;
            align-items: center;
            position: absolute;
        }

        #admission_table_filter {
            margin-bottom: 1rem;
        }

        .select2-container--open .select2-dropdown--below {
            z-index: 9999;
        }

        a.page-link {
            color: #666 !important;
            border: none;
        }

        .active>.page-link,
        .page-link.active,
        .page-link:hover {
            background-color: #18a8b0;
            border-color: #18a8b0;
            color: #fff !important;
        }

        .disabled>.page-link {
            border: none;
        }

        div.dataTables_processing>div:last-child>div {
            background-color: #18a8b0;
        }

        .data-table-header {
            width: calc(100% - 130px);
            position: relative;
            left: 130px;
        }

        .dt-buttons {
            position: relative !important;
            bottom: 5px;
        }

        div.dt-buttons>.dt-button {
            background: linear-gradient(to bottom, rgb(255 180 45) 0%, rgb(255 180 45) 100%);
            border: 1px solid rgb(255 180 45);
            color: #fff;
        }

        #admission_table_wrapper .data-table-header {
            display: grid !important;
            grid-template-columns: 88% 10%;
        }

        #admission_table_wrapper .dt-buttons {
            text-align: right;
        }

        #admission_table_wrapper div#admission_table_filter {
            margin: auto;
        }

        table.dataTable>thead>tr>th {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            color: #636578;
            font-weight: 500px;
            background-color: #F8F9FA;
        }

        .status-button {
            width: 110px;
            pointer-events: none;
        }

        @media screen and (max-width:1366px) {
            .mobile-pagination {
                display: block !important;
                text-align: center;
            }

            ul.pagination {
                justify-content: center;
            }
        }

        @media screen and (max-width: 1024px) {
            #admission_table_wrapper .data-table-header {
                grid-template-columns: 74% 24%;
            }
        }

        @media screen and (max-width: 768px) {
            .mobile-pagination {
                flex-wrap: wrap;
                width: 100%;
                justify-content: center !important;
            }

            .mobile-pagination p {
                font-size: 15px;
                text-align: center;
            }

            .mobile-pagination li a {
                font-size: 13px !important;
            }

            .mobile-pagination li {
                padding-right: 0 !important;
            }

            .mobile-pagination li span {
                font-size: 13px;
                background: transparent !important;
            }

            .mobile-pagination li {
                background: transparent;
            }

            .mobile-pagination ul li.active span {
                background: #18a8b0 !important;
            }

            .mobile-pagination .pagination {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            select#selPagesBottom {
                top: 36px;
                right: 41px;
            }
        }

        @media screen and (max-width: 425px) {
            .divBottom {
                display: block;
            }

            select#selPagesBottom {
                top: 0px;
                left: 130px;
            }

            select#selPagesUp {
                margin: 13px auto 0;
            }
        }

        /* Overlay styles */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* semi-transparent black */
            z-index: 9999;
            display: block;
            /* Initially hidden */
            overflow-x: hidden !important;
        }

        .pac-container {
            z-index: 99999 !important;
        }
    </style>
@endsection
@section('content')
    <div class="dashboard-header-container">
        <div class="d-flex d-board-inr">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="sklps-mb-logo">
                <img src="/assets/images/sklps-logo.png" alt="Logo" class="img-fluid">
            </div>
            <h3 class="dashboard-header">Admission</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Admission', 'create');

            if ($chk) {
                echo "<a href='" . route('admission.create') . "' class='btn primary_btn add_btn'>Add</a>";
            }
        @endphp

    </div>
    <form id="filter_form">
        <input type="hidden" name="page_length" id="page_length" value="{{ $request->page_length }}">
        <div class="admission-filter-bar">
            <select class="form-select select2" name="year" id="year" aria-label="Default select example"
                data-placeholder="Select Year">
                <option value="" selected>Select Year</option>
                @foreach ($yearList as $item)
                    <option @if ($item == $request->year) selected="selected" @endif value="{{ $item }}">
                        {{ $item }}</option>
                    {{-- <option @if ($item == date('Y') . '-' . date('Y', strtotime(' +1 year'))) selected="selected" @endif value="{{ $item }}">
                        {{ $item }}</option> --}}
                @endforeach
            </select>
            <select class="form-select select2" name="gender" id="gender" aria-label="Default select example"
                data-placeholder="Select Gender">
                <option value="" selected>Select Gender</option>
                <option value="boy" @if ($request->gender == 'boy') selected @endif>Boy</option>
                <option value="girl" @if ($request->gender == 'girl') selected @endif>Girl</option>
            </select>
            <select class="form-select select2" name="status" id="status" aria-label="Default select example"
                data-placeholder="Select Admission Status">
                <option value="" selected>Select Admission Status</option>
                <option value="0" @if ($request->status == '0') selected @endif>Pending</option>
                <option value="1" @if ($request->status == '1') selected @endif>Confirm</option>
                <option value="2" @if ($request->status == '2') selected @endif>Reject</option>
                <option value="3" @if ($request->status == '3') selected @endif>Cancelled
                </option>
                <option value="4" @if ($request->status == '4') selected @endif>Release</option>
            </select>
            <select class="form-select select2" name="courseId" id="courseId" aria-label="Default select example"
                data-placeholder="Select Course">
                <option value="" selected>Select Course</option>
                @foreach ($courses as $item)
                    <option value="{{ $item->id }}" @if ($request->courseId == $item->id) selected @endif>
                        {{ $item->course_name }}</option>
                @endforeach
            </select>
            <select class="form-select select2" name="roomAlloted" id="roomAlloted" aria-label="Default select example"
                data-placeholder="Select Room Alloted">
                <option value="" selected>Select Room Alloted</option>
                <option value="no" @if ($request->roomAlloted == 'no') selected @endif>No</option>
                <option value="yes" @if ($request->roomAlloted == 'yes') selected @endif>Yes</option>
            </select>
            <select class="form-select select2" name="isAdmissionNew" id="isAdmissionNew"
                aria-label="Default select example" data-placeholder="Select New/Old">
                <option value="" selected>Select New/Old</option>
                <option value="1" @if ($request->isAdmissionNew == '1') selected @endif>New</option>
                <option value="0" @if ($request->isAdmissionNew == '0') selected @endif>Old</option>
            </select>
            <div class="d-flex gap-1">
                <button class="btn primary_btn" type="submit" id="filter" name="filter">Filter</button>
                <button class="btn secondary_btn" type="submit" name="reset" id="reset">Reset</button>
            </div>
        </div>
    </form>
    <div class="card">
        <div id="alert-container"></div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show mb-0" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="table_content_wrapper">
            <div class="data_table_wrap">
                <div class="row page-wrapper">
                    <div class="col-md-2 page_wrapper">
                        Show
                        <select id="selPagesUp" class="selPages form-select p-2">
                            <option @if ($request->page_length == '10') selected @endif value="10">10</option>
                            <option @if ($request->page_length == '25') selected @endif value="25">25</option>
                            <option @if ($request->page_length == '50') selected @endif value="50">50</option>
                            <option @if ($request->page_length == '100') selected @endif value="100">100</option>
                            <option @if ($request->page_length == '200') selected @endif value="200">200</option>
                            <option @if ($request->page_length == '500') selected @endif value="500">500</option>
                            <option @if ($request->page_length == '1000') selected @endif value="1000">1000</option>
                            <option @if ($request->page_length == '5000') selected @endif value="5000">5000</option>
                            <option @if ($request->page_length == '10000') selected @endif value="10000">10000</option>
                        </select>
                        entries
                    </div>
                </div>
                <table class="datatables-basic table table-bordered" id="admission_table">
                    <!-- Overlay HTML -->
                    <div id="overlay">
                        <div class="dataTables_processing card" role="status">
                            <div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Action</th>
                            <th>Sr. No.</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Mobile Number</th>
                            <th>Email Id</th>
                            <th>Village</th>
                            {{-- <th>Father's Name</th> --}}
                            {{-- <th>Father's No</th> --}}
                            <th>Qualification</th>
                            {{-- <th>Semester</th> --}}
                            <th>Collage Name/Institute Name</th>
                            <th>Admission Date</th>
                            <th>Admission Old/New</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allData as $key => $item)
                            <tr>
                                <td></td>
                                <td>
                                    @php
                                        $html = '';
                                        $readCheck = \App\Models\Permission::checkCRUDPermissionToUser(
                                            'Admission',
                                            'read',
                                        );
                                        $updateCheck = \App\Models\Permission::checkCRUDPermissionToUser(
                                            'Admission',
                                            'update',
                                        );
                                        $isSuperAdmin = \App\Models\Permission::isSuperAdmin();
                                        if ($updateCheck) {
                                            $html .=
                                                '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="admission/' .
                                                $item->id .
                                                '/edit">Edit</a></li>';
                                        }
                                        if ($isSuperAdmin) {
                                            $html .=
                                                '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="sendStatusRemark(' .
                                                $item->id .
                                                ')">Admission Status</a></li>';

                                        }
                                        if($chk){
                                            if ($item->is_bed_release == 0) {
                                                if ($item->is_fees_paid == 0 && $item->is_admission_confirm == 0) {
                                                    /* $html .=
                                                        '<p class="m-0 px-3 text-primary">Admission not Confirm</p>'; */
                                                } elseif ($item->is_admission_confirm == 0) {
                                                    /* $html .= '<p class="m-0 px-3">Admission not Confirm</p>'; */
                                                } else {
                                                    if ($item->is_admission_confirm == 1) {
                                                        $html .=
                                                            '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="addFees(' .
                                                            $item->id .
                                                            ')">Add Fees</a></li>';
                                                        $html .=
                                                            '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="roomAllocate(' .
                                                            $item->id .
                                                            ')">Room Allot</a></li>';
                                                    } elseif ($item->is_admission_confirm == 3) {
                                                        /* $html .=
                                                            '<p class="m-0 px-3 text-info"">Admission Cancelled</p>'; */
                                                    } else {
                                                        /* $html .=
                                                            '<p class="m-0 px-3 text-danger">Admission Rejected</p>'; */
                                                    }
                                                }
                                            } else {
                                                /*   */
                                                // $html .= 'Release';
                                            }
                                        }
                                        if (!$isSuperAdmin && !$updateCheck && !$readCheck) {
                                            $html = '';
                                        }
                                        if ($readCheck) {
                                            if ($item->fees_status == 'Paid') {
                                                $html .=
                                                    '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="feesReceipt(' .
                                                    $item->id .
                                                    ')">View Receipt</a></li>';
                                            }
                                            $html .=
                                                '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="admission/' .
                                                $item->id .
                                                '">View </a></li>';
                                        }
                                        if ($isSuperAdmin) {
                                            $html .=
                                                '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect"  href="javascript:void(0)" data-id="' .
                                                $item->id .
                                                '" onclick="deleteAdmission(' .
                                                $item->id .
                                                ')" >Delete</a></li>';
                                        }
                                    @endphp
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-sm btn-warning p-1 dropdown-toggle hide-arrow text-white action-button"
                                                    data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    {!! $html !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->full_name }}</td>
                                <td>{{ ucfirst($item->gender) }}</td>
                                @php
                                    $statusMap = [
                                        0 => ['label' => 'Pending', 'class' => 'btn-outline-secondary'],
                                        1 => ['label' => 'Confirm', 'class' => 'btn-outline-success'],
                                        2 => ['label' => 'Reject', 'class' => 'btn-outline-danger'],
                                        3 => ['label' => 'Cancelled', 'class' => 'btn-outline-info'],
                                        4 => ['label' => 'Release', 'class' => 'btn-outline-warning'],
                                    ];

                                    $status = $statusMap[$item->is_admission_confirm] ?? [
                                        'label' => 'Unknown',
                                        'class' => 'text-muted',
                                    ];
                                @endphp

                                <td> <button type="button"
                                        class="btn {{ $status['class'] }} status-button">{{ $status['label'] }}</button>
                                </td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->village_name }}</td>
                                {{-- <td>{{ $item->father_full_name }}</td> --}}
                                {{-- <td>{{ $item->father_phone }}</td> --}}
                                <td>{{ $item->course_name }}</td>
                                {{-- <td>{{ $item->semester }}</td> --}}
                                <td>{{ $item->institute_name }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->isAdmissionNew == 0 ? 'Old' : 'New' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row page-wrapper divBottom pt-2">
                    {{--  <div class="col m4">
                        <label class="paginate-info">Showing {{ $allData->firstItem() }} to {{ $allData->lastItem() }} of
                            {{ $allData->total() }} entries</label>
                    </div> --}}
                    <div class="col col-md-12">
                        {{-- {{dd($allData->links())}} --}}
                        {!! $allData->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Admin Remark -->
    <div class="modal fade createStatusRemarkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Remarks</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="createStatusRemarkForm" action="{{ route('admission.sendStatusRemark') }}" method="post"
                    class="fees_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admission_status">Admission Status</label>
                                <select name="admission_status" id=""
                                    class="select2 form-select admission_status"
                                    data-placeholder="Select Admission Status"
                                    data-parsley-errors-container="#admission_status_errors" required>
                                    <option value="">Select Admission Status</option>
                                    <option @if (old('admission_status') == '0') selected @endif value="0" selected>
                                        Pending
                                    </option>
                                    <option @if (old('admission_status') == '1') selected @endif value="1">
                                        Confirm
                                    </option>
                                    <option @if (old('admission_status') == '2') selected @endif value="2">Reject
                                    </option>
                                    <option @if (old('admission_status') == '3') selected @endif value="3">
                                        Cancelled
                                    </option>
                                    <option @if (old('admission_status') == '4') selected @endif value="4">Release
                                    </option>
                                </select>
                                <div id="admission_status_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="admin_comment">Admin Comment</label>
                                <textarea class="form-control admin_comment complaint_desc_field" name="admin_comment" id=""
                                    placeholder="Enter Admin Comment" required data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn ">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Fees Modal -->
    <div class="modal fade createFeesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Fees</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createFeesForm" action="{{ route('fees.store') }}" method="post" class="fees_form"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="fees_amount">Amount</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" step="any" min="0"
                                        name="fees_amount" id="fees_amount" value="{{ old('fees_amount') }}"
                                        placeholder="Enter Amount" data-parsley-errors-container="#fees_amount_errors"
                                        required data-parsley-required-message="The amount field is required." />
                                    @error('fees_amount')
                                        <small class="red-text ml-10" role="alert"
                                            style="position: absolute; margin-left: -25px;">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <span class="amount_error d-none" style="color: red;">Cash Not Allowed Above
                                    ₹20,000</span>
                                <div id="fees_amount_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="payment_type">Payment Type</label>
                                <select name="payment_type" id="payment_type" class="select2 form-select"
                                    data-placeholder="Select Payment Type"
                                    data-parsley-errors-container="#payment_type_errors" required
                                    data-parsley-required-message="The payment type field is required.">
                                    <option value="">Select Payment Type</option>
                                    <option @if (old('payment_type') == 'Cash') selected @endif value="Cash">Cash
                                    </option>
                                    <option @if (old('payment_type') == 'Bank') selected @endif value="Bank">Bank
                                        Transfer</option>
                                    <option @if (old('payment_type') == 'Cheque') selected @endif value="Cheque">Cheque
                                    </option>
                                    <option @if (old('payment_type') == 'Card') selected @endif value="Card">Credit
                                        Card</option>
                                </select>
                                @error('payment_type')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <div id="payment_type_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 transaction_number_div d-none">
                                <label for="transaction_number">Transaction Number</label>
                                <input type="number" class="form-control" name="transaction_number"
                                    id="transaction_number" value="{{ old('transaction_number') }}"
                                    placeholder="Enter Transaction Number"
                                    data-parsley-required-message="The transaction number field is required." />
                                @error('transaction_number')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 bank_name_div d-none">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" id="bank_name"
                                    value="{{ old('bank_name') }}" placeholder="Enter Bank Name"
                                    data-parsley-required-message="The bank name field is required." />
                                @error('bank_name')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 cheque_number_div d-none">
                                <label for="cheque_number">Cheque Number</label>
                                <input type="text" class="form-control" name="cheque_number" id="cheque_number"
                                    value="{{ old('cheque_number') }}" placeholder="Enter Cheque Number"
                                    data-parsley-required-message="The cheque number field is required." />
                                @error('cheque_number')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="donation_type">Donation Type</label>
                                <select name="donation_type" id="donation_type" class="select2 form-select"
                                    data-placeholder="Select Donation Type"
                                    data-parsley-errors-container="#donation_type_errors" required
                                    data-parsley-required-message="The donation type field is required.">
                                    <option value="">Select Donation Type</option>
                                    <option @if (old('donation_type') == 'Vidhyadan') selected @endif value="Vidhyadan">
                                        Vidhyadan</option>
                                    <option @if (old('donation_type') == 'Secure Fund') selected @endif value="Secure Fund">
                                        Security Deposite</option>
                                </select>
                                @error('donation_type')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <div id="donation_type_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="payment_method">Donation Type</label>
                                <select name="payment_method" id="payment_method" class="select2 form-select"
                                    data-placeholder="Select Payment Method"
                                    data-parsley-errors-container="#payment_method_errors" required
                                    data-parsley-required-message="The payment method field is required.">
                                    <option value="">Select Payment Method</option>
                                    <option @if (old('payment_method') == 'Monthly') selected @endif value="Monthly">
                                        Monthly</option>
                                    <option @if (old('payment_method') == 'Quarterly') selected @endif value="Quarterly">
                                        Quarterly</option>
                                    <option @if (old('payment_method') == 'Half-Yearly') selected @endif value="Half-Yearly">
                                        Half Yearly</option>
                                    <option @if (old('payment_method') == 'Yearly') selected @endif value="Yearly">Yearly
                                    </option>
                                </select>
                                @error('payment_method')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                                <div id="payment_method_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="remarks">Remarks</label>
                                <input type="text" class="form-control" name="remarks" value="{{ old('remarks') }}"
                                    id="remarks" placeholder="Enter Remarks" />
                                @error('remarks')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn ">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Fees Receipt -->
    <div class="modal fade viewFeesReceiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title student_name"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="viewFeesReceiptTable" style="overflow-x: auto;">
                                <thead>
                                    <tr>
                                        <th>SR No.</th>
                                        <th>Slip No.</th>
                                        <th>Payment Type</th>
                                        <th>Amount</th>
                                        <th>Paid At</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocate room bed -->
    <div class="modal fade roomAllocateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Room Allotment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="roomAllocateForm" action="{{ route('reservation.store') }}" method="post"
                    class="roomAllocateForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Name</label>
                                <input type="text" class="form-control student_name" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="name">Student Email</label>
                                <input type="text" class="form-control student_email" disabled />
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="hostel_id">Hostel</label>
                                <select name="hostel_id" id="hostel_id" class="select2 form-select"
                                    data-placeholder="Select Hostel" data-parsley-errors-container="#hostel_id_errors"
                                    required data-parsley-required-message="The hostel field is required.">
                                    <option value="">Select Hostel</option>
                                    @foreach ($hostels as $hostel)
                                        <option value="{{ $hostel->id }}">
                                            {{ $hostel->hostel_name }}</option>
                                    @endforeach
                                </select>
                                <div id="hostel_id_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="room_id">Room</label>
                                <select name="room_id" id="room_id" class="select2 form-select"
                                    data-placeholder="Select Room" data-parsley-errors-container="#room_id_errors"
                                    required data-parsley-required-message="The room field is required.">
                                    <option value="">Select Room</option>
                                </select>
                                <div id="room_id_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="bed_id">Bed</label>
                                <select name="bed_id" id="bed_id" class="select2 form-select"
                                    data-placeholder="Select Bed" data-parsley-errors-container="#bed_id_errors" required
                                    data-parsley-required-message="The bed field is required.">
                                    <option value="">Select Bed</option>
                                </select>
                                <div id="bed_id_errors"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 px-4">
                                <div id="no_allotment_section"><b>Your Allotment is pending.</b></div>
                                <div id="room_allotment_section" class="d-none">
                                    <div class="d-flex gap-2"><b>Hostel Name:- </b>
                                        <p id="hostel_name_text"></p>
                                    </div>
                                    <div class="d-flex gap-2"><b>Room Number:- </b>
                                        <p id="room_name_text"></p>
                                    </div>
                                    <div class="d-flex gap-2"><b>Bed Number:- </b>
                                        <p id="bed_name_text"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn secondary_btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn primary_btn ">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admission/admission.js') }}"></script>
@endsection
