@extends('backend.layouts.app')
@section('title', 'Admission Listing')
@section('styles')
    {{-- <style>
        div.dataTables_length {
            margin-left: -68px;
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

        .card-datatable {
            min-height: 200px;
        }

        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-button,
        input[type="search"]::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        select#selPagesUp {
            position: absolute;
            width: auto;
            top: 84px;
            z-index: 9;
        }

        .disabled>.page-link {
            background-color: transparent;
        }

        .form-floating-outline label::after .filter_label {
            background: #C7A45F;
        }

        div.dataTables_wrapper div.col-sm-12 {
            padding: 0 !important;
        }

        /* Overlay styles */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            display: block;
            overflow-x: hidden !important;
        }

        [type="search"]::-webkit-search-cancel-button {
            -webkit-appearance: none;
            appearance: none;
            height: 10px;
            width: 10px;
            background-image: url('{{ asset('assets/img/branding/search-close.png') }}');
            background-size: 10px 10px;
        }

        div.dataTables_wrapper div.dataTables_length select {
            width: 80px;
        }

        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
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
                background: #ffb422 !important;
            }

            .mobile-pagination .pagination {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            select#selPagesUp {
                position: static;
                width: auto;
                top: 115px;
                margin: 13px auto 0;
            }
        }

        @media screen and (max-width: 425px) {
            select#selPagesUp {
                margin: 13px auto 0;
            }

            .modal-dialog {
                display: flex;
                align-items: center;
                min-height: calc(100% - var(--bs-modal-margin)* 2);
            }
        }
    </style> --}}
@endsection
@section('content')
    {{-- <div class="card mb-3">
        <div class="card-body">
            <form>
                <input type="hidden" name="page_length" id="page_length" value="{{ $request->page_length }}">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="year" id="year"
                                aria-label="Default select example" data-placeholder="Select Year">
                                <option value="" selected>Select Year</option>
                                @foreach ($yearList as $item)

                                    <option @if ($item == date('Y') . '-' . date('Y', strtotime(' +1 year'))) selected="selected" @endif
                                        value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="gender" id="gender"
                                aria-label="Default select example" data-placeholder="Select Gender">
                                <option value="" selected>Select Gender</option>
                                <option value="boy" @if ($request->gender == 'boy') selected @endif>Boy</option>
                                <option value="girl" @if ($request->gender == 'girl') selected @endif>Girl</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="status" id="status"
                                aria-label="Default select example" data-placeholder="Select Admission Status">
                                <option value="" selected>Select Admission Status</option>
                                <option value="0" @if ($request->status == '0') selected @endif>Pending</option>
                                <option value="1" @if ($request->status == '1') selected @endif>Confirm</option>
                                <option value="2" @if ($request->status == '2') selected @endif>Reject</option>
                                <option value="3" @if ($request->status == '3') selected @endif>Cancelled
                                </option>
                                <option value="4" @if ($request->status == '4') selected @endif>Release</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="courseId" id="courseId"
                                aria-label="Default select example" data-placeholder="Select Course">
                                <option value="" selected>Select Course</option>
                                @foreach ($courses as $item)
                                    <option value="{{ $item->id }}">{{ $item->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="roomAlloted" id="roomAlloted"
                                aria-label="Default select example" data-placeholder="Select Room Alloted">
                                <option value="" selected>Select Room Alloted</option>
                                <option value="no" @if ($request->roomAlloted == 'no') selected @endif>No</option>
                                <option value="yes" @if ($request->roomAlloted == 'yes') selected @endif>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 mb-4 mb-sm-0">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select select2" name="isAdmissionNew" id="isAdmissionNew"
                                aria-label="Default select example" data-placeholder="Select New/Old">
                                <option value="" selected>Select New/Old</option>
                                <option value="1" @if ($request->isAdmissionNew == '1') selected @endif>New</option>
                                <option value="0" @if ($request->isAdmissionNew == '0') selected @endif>Old</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 d-flex align-items-center gap-2">
                        <button class="btn btn-primary" type="submit" id="filter" name="filter">Filter</button>
                        <button class="btn btn-primary" type="submit" name="reset" id="reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

    <div class="col-md-10 main-content">
        <div class="dashboard-header-container">
          <h3 class="dashboard-header">Admissions</h3>
          <button class="primary_btn add_btn">Add</button>
        </div>

        <div class="admission-filter-bar">
          <select class="form-select">
            <option>2024-2025</option>
          </select>
          <select class="form-select">
            <option>Female</option>
          </select>
          <select class="form-select">
            <option>Admission Status</option>
          </select>
          <select class="form-select">
            <option>Course</option>
          </select>
          <select class="form-select">
            <option>Room Allotted</option>
          </select>
          <select class="form-select">
            <option>New/Old</option>
          </select>
          <button class="primary_btn">Filter</button>
        </div>

        <div class="data_table_wrap">
          <table id="admissionsTable" class="table table-bordered table-striped align-middle" style="width: 100%">
            <thead class="table-light">
              <tr>
                <th><i class="bi bi-plus-circle"></i></th>
                <th>Action</th>
                <th>ID</th>
                <th>Company Name</th>
                <th>Owner Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Mobile</th>
                <th>Contact Person Name</th>
                <th>Supplier Type</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be inserted by JS -->
            </tbody>
          </table>
        </div>
      </div>

    {{-- <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">Admissions</h5>
            @php
                $chk = \App\Models\Permission::checkCRUDPermissionToUser('Admission', 'create');
                if ($chk) {
                    echo "<a href='" .
                        route('admission.create') .
                        "' class='btn btn-primary waves-effect waves-light text-white'><span class='d-md-none d-sm-inline-block'><i class='mdi mdi-plus me-sm-1'></i></span> <span class='d-none d-sm-inline-block'>Add Admission</span></a>";
                }
            @endphp
        </div>
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body pt-0">
            <div class="card-datatable table-responsive pt-0">
                <div class="row page-wrapper">
                    <div class="col-md-1">
                        <select id="selPagesUp" class="selPages form-select py-1">
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
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Mobile Number</th>
                            <th>Email Id</th>
                            <th>Village</th>
                            <th>Father's Name</th>
                            <th>Father's No</th>
                            <th>Qualification</th>
                            <th>Semester</th>
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

                                            if ($item->is_bed_release == 0) {
                                                if ($item->is_fees_paid == 0 && $item->is_admission_confirm == 0) {
                                                    $html .=
                                                        '<p class="m-0 px-3 text-primary">Admission not Confirm</p>';
                                                } elseif ($item->is_admission_confirm == 0) {
                                                    $html .= '<p class="m-0 px-3">Admission not Confirm</p>';
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
                                                        $html .=
                                                            '<p class="m-0 px-3 text-info"">Admission Cancelled</p>';
                                                    } else {
                                                        $html .=
                                                            '<p class="m-0 px-3 text-danger">Admission Rejected</p>';
                                                    }
                                                }
                                            } else {
                                                $html .= '<p class="m-0 px-3 text-danger"">Student Left</p>';
                                                // $html .= 'Release';
                                            }

                                            /* if ($item->is_bed_release == 0) {
                                                if ($item->is_admission_confirm == 0) {
                                                    $html .= '<p class="m-0 px-3">Admission not Confirm</p>';
                                                } else {
                                                    if ($item->is_admission_confirm == 1) {
                                                        $html .=
                                                            '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="roomAllocate(' .
                                                            $item->id .
                                                            ')">Room Allot</a></li>';
                                                    } elseif ($item->is_admission_confirm == 3) {
                                                        $html .= '<p class="m-0 px-3">Admission Cancelled</p>';
                                                    } else {
                                                        $html .= '<p class="m-0 px-3">Admission Rejected</p>';
                                                    }
                                                }
                                            } else {
                                                $html .= 'Release';
                                            } */

                                            if ($item->fees_status == 'Paid') {
                                                $html .=
                                                    '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect StatusRemark" href="javascript:void(0)" onclick="feesReceipt(' .
                                                    $item->id .
                                                    ')">View Receipt</a></li>';
                                            }
                                        }
                                        if (!$isSuperAdmin && !$updateCheck && !$readCheck) {
                                            $html = '';
                                        }
                                        if ($readCheck) {
                                            $html .=
                                                '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="admission/' .
                                                $item->id .
                                                '">View </a></li>';
                                        }
                                    @endphp
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn btn-primary p-1 dropdown-toggle hide-arrow"
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
                                <td>{{ $item->full_name }}</td>
                                <td>{{ $item->gender }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->village_name }}</td>
                                <td>{{ $item->father_full_name }}</td>
                                <td>{{ $item->father_phone }}</td>
                                <td>{{ $item->course_name }}</td>
                                <td>{{ $item->semester }}</td>
                                <td>{{ $item->institute_name }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->isAdmissionNew == 0 ? 'Old' : 'New' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row page-wrapper">
                    <div class="col col-md-12">
                        {!! $allData->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <!-- Send Admin Remark -->
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
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_name" disabled />
                                    <label for="name">Student Name</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_email" disabled />
                                    <label for="name">Student Email</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
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
                                    <label for="admission_status">Admission Status</label>
                                    <div id="admission_status_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control admin_comment" name="admin_comment" id="" cols="30" rows="10"
                                        placeholder="Enter Admin Comment" required data-parsley-required-message="The admin comment field is required.">{{ old('admin_comment') }}</textarea>
                                    <label for="admin_comment">Admin Comment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_name" disabled />
                                    <label for="name">Student Name</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_email" disabled />
                                    <label for="name">Student Email</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control" step="any" min="0"
                                            name="fees_amount" id="fees_amount" value="{{ old('fees_amount') }}"
                                            placeholder="Enter Amount" data-parsley-errors-container="#fees_amount_errors"
                                            required data-parsley-required-message="The amount field is required." />
                                        <label for="fees_amount">Amount</label>
                                        @error('fees_amount')
                                            <small class="red-text ml-10" role="alert"
                                                style="position: absolute; margin-left: -25px;">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <span class="amount_error d-none" style="color: red;">Cash Not Allowed Above
                                    ₹20,000</span>
                                <div id="fees_amount_errors"></div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
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
                                    <label for="payment_type">Payment Type</label>
                                    @error('payment_type')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                    <div id="payment_type_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 transaction_number_div d-none">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="transaction_number"
                                        id="transaction_number" value="{{ old('transaction_number') }}"
                                        placeholder="Enter Transaction Number"
                                        data-parsley-required-message="The transaction number field is required." />
                                    <label for="transaction_number">Transaction Number</label>
                                    @error('transaction_number')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 bank_name_div d-none">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="bank_name" id="bank_name"
                                        value="{{ old('bank_name') }}" placeholder="Enter Bank Name"
                                        data-parsley-required-message="The bank name field is required." />
                                    <label for="bank_name">Bank Name</label>
                                    @error('bank_name')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4 cheque_number_div d-none">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="cheque_number" id="cheque_number"
                                        value="{{ old('cheque_number') }}" placeholder="Enter Cheque Number"
                                        data-parsley-required-message="The cheque number field is required." />
                                    <label for="cheque_number">Cheque Number</label>
                                    @error('cheque_number')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
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
                                    <label for="donation_type">Donation Type</label>
                                    @error('donation_type')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                    <div id="donation_type_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
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
                                    <label for="payment_method">Donation Type</label>
                                    @error('payment_method')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                    <div id="payment_method_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="remarks"
                                        value="{{ old('remarks') }}" id="remarks" placeholder="Enter Remarks" />
                                    <label for="remarks">Remarks</label>
                                    @error('remarks')
                                        <small class="red-text ml-10" role="alert">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
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
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_name" disabled />
                                    <label for="name">Student Name</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_email" disabled />
                                    <label for="name">Student Email</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="hostel_id" id="hostel_id" class="select2 form-select"
                                        data-placeholder="Select Hostel" data-parsley-errors-container="#hostel_id_errors"
                                        required>
                                        <option value="">Select Hostel</option>
                                        @foreach ($hostels as $hostel)
                                            <option value="{{ $hostel->id }}">
                                                {{ $hostel->hostel_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="hostel_id">Hostel</label>
                                    <div id="hostel_id_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="room_id" id="room_id" class="select2 form-select"
                                        data-placeholder="Select Room" data-parsley-errors-container="#room_id_errors"
                                        required>
                                        <option value="">Select Room</option>
                                    </select>
                                    <label for="room_id">Room</label>
                                    <div id="room_id_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="bed_id" id="bed_id" class="select2 form-select"
                                        data-placeholder="Select Bed" data-parsley-errors-container="#bed_id_errors"
                                        required>
                                        <option value="">Select Bed</option>
                                    </select>
                                    <label for="bed_id">Bed</label>
                                    <div id="bed_id_errors"></div>
                                </div>
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
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection
@section('scripts')
    <script src="{{ asset('js/admission/admission.js') }}"></script>
@endsection
