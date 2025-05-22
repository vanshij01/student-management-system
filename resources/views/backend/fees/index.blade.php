@extends('backend.layouts.app')
@section('title', 'Donation Listing')
@section('styles')
    <style>
        .rupees-icon {
            position: absolute;
            right: unset;
            left: 15px;
            bottom: unset;
            top: 22px;
            background: none;
            padding: 0px !important;
            border: none !important;
            font-size: 20px !important;
        }

        #fees_amount,
        #update_fees_amount {
            padding: 14.5px 20px 14.5px 34.5px;
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
            <h3 class="dashboard-header">Donation</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Fee', 'create');

            if ($chk) {
                echo "<a class='btn primary_btn add_btn' id='addButton'>Add</a>";
            }
        @endphp

    </div>
    <form>
        <div class="admission-filter-bar">
            <input type="text" class="form-control" placeholder="From" name="from" id="from"
                value="{{ old('from') }}" autocomplete="off"/>
            <input type="text" class="form-control" placeholder="To" name="to" id="to"
                value="{{ old('to') }}" autocomplete="off"/>
            <select class="form-select select2" name="student_id" id="student_id" aria-label="Default select example"
                data-placeholder="Select Student">
                <option value="" selected>Select Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                @endforeach
            </select>
            <select class="select2 form-select" id="gender" name="gender" data-placeholder="Select Gender">
                <option value="">Select Gender</option>
                <option value="girl">Girl</option>
                <option value="boy">Boy</option>
            </select>
            <select class="form-select select2" name="hostel_id" id="hostel_id" aria-label="Default select example"
                data-placeholder="Select Hostel">
                <option value="" selected>Select Hostel</option>
                @foreach ($hostels as $hostel)
                    <option value="{{ $hostel->id }}">{{ $hostel->hostel_name }}</option>
                @endforeach
            </select>
            <div class="d-flex gap-1">
                <button class="primary_btn" type="button" id="filter" name="filter">Filter</button>
                <button class="secondary_btn" type="button" name="reset" id="reset">Reset</button>
            </div>
        </div>
    </form>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="fees_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Action</th>
                        <th>Sr. No.</th>
                        <th>Slip No.</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Hostel Name</th>
                        <th>Payment Type</th>
                        <th>Payment Term</th>
                        <th>Amount</th>
                        <th>Paid At</th>
                        <th>Admission Year</th>
                        <th>Date</th>
                        <th>Transaction Number</th>
                        <th>Donation Type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Create Fees Modal -->
    <div class="modal fade" id="createFeesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Fees</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createFeesForm" action="{{ route('fees.store') }}" method="post" class="fees_form"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="frm_type" value="modal">
                    <input type="hidden" name="page" value="modal">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <input type="hidden" name="admission_id" id="admission_id_input">
                                <label for="admission_id">Student</label>
                                <select class="form-select select2" id="admission_id" name="admission_id"
                                    aria-label="Default select example" data-placeholder="Select Student"
                                    data-parsley-errors-container="#admission_id_errors" required
                                    data-parsley-required-message="The student name field is required.">
                                    <option value="" selected>Select Student</option>
                                    @foreach ($admissions as $student)
                                        <option value="{{ $student->id }}">{{ $student->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="admission_id_errors"></div>
                                @error('admission_id')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                {{-- <div class="input-group input-group-merge">
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
                                <div id="fees_amount_errors"></div> --}}
                                <div class="input-box">
                                    <label for="fees_amount">Amount</label>
                                    <div class="form-password-toggle">
                                        <div class="input-group input-group-merge d-block">
                                            <div>
                                                <span class="input-group-text cursor-pointer rupees-icon"><i
                                                        class="las la-rupee-sign"></i></span>
                                                <input type="number" class="form-control" step="any" min="0"
                                                    name="fees_amount" id="fees_amount" value="{{ old('fees_amount') }}"
                                                    placeholder="Enter Amount"
                                                    data-parsley-errors-container="#fees_amount_errors" required
                                                    data-parsley-required-message="The amount field is required." />
                                                @error('fees_amount')
                                                    <small class="red-text ml-10" role="alert"
                                                        style="position: absolute; margin-left: -25px;">
                                                        {{ $message }}
                                                    </small>
                                                @enderror
                                                <span class="amount_error d-none" style="color: red;">Cash Not Allowed
                                                    Above
                                                    ₹20,000</span>
                                            </div>
                                        </div>
                                        <div id="password_errors"></div>
                                    </div>
                                </div>
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

    <!-- Update Fees Modal -->
    <div class="modal fade" id="updateFeesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Fees</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateFeesForm" method="post" class="fees_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="update_fees_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <input type="hidden" name="admission_id" id="admission_id_input">
                                <label for="admission_id">Student</label>
                                <select class="form-select select2" id="update_admission_id" name="admission_id"
                                    aria-label="Default select example" data-placeholder="Select Student"
                                    data-parsley-errors-container="#admission_id_errors" required
                                    data-parsley-required-message="The student name field is required.">
                                    <option value="" selected>Select Student</option>
                                    @foreach ($admissions as $student)
                                        <option value="{{ $student->id }}">{{ $student->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="admission_id_errors"></div>
                                @error('admission_id')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                {{-- <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" class="form-control" step="any" min="0"
                                            name="fees_amount" id="update_fees_amount" value="{{ old('fees_amount') }}"
                                            placeholder="Enter Amount" data-parsley-errors-container="#fees_amount_errors"
                                            required readonly
                                            data-parsley-required-message="The amount field is required." />
                                        <label for="fees_amount">Amount</label>
                                        @error('Amount')
                                            <small class="red-text ml-10" role="alert"
                                                style="position: absolute; margin-left: -25px;">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                        <span class="amount_error d-none" style="color: red;">Cash Not Allowed Above
                                            ₹20,000</span>
                                    </div>
                                </div>
                                <div id="fees_amount_errors"></div> --}}

                                <div class="input-box">
                                    <label for="fees_amount">Amount</label>
                                    <div class="form-password-toggle">
                                        <div class="input-group input-group-merge d-block">
                                            <div>
                                                <span class="input-group-text cursor-pointer rupees-icon"><i
                                                        class="las la-rupee-sign"></i></span>
                                                <input type="number" class="form-control" step="any" min="0"
                                                    name="fees_amount" id="update_fees_amount"
                                                    value="{{ old('fees_amount') }}" placeholder="Enter Amount"
                                                    data-parsley-errors-container="#fees_amount_errors" required readonly
                                                    data-parsley-required-message="The amount field is required." />
                                                @error('fees_amount')
                                                    <small class="red-text ml-10" role="alert"
                                                        style="position: absolute; margin-left: -25px;">
                                                        {{ $message }}
                                                    </small>
                                                @enderror
                                                <span class="amount_error d-none" style="color: red;">Cash Not Allowed
                                                    Above
                                                    ₹20,000</span>
                                            </div>
                                        </div>
                                        <div id="password_errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="payment_type">Payment Type</label>
                                <select name="payment_type" id="update_payment_type" class="select2 form-select"
                                    data-placeholder="Select Payment Type"
                                    data-parsley-errors-container="#payment_type_errors" required
                                    data-parsley-required-message="The payment type field is required.">
                                    <option value="">Select Payment Type</option>
                                    <option @if (old('payment_type') == 'Cash') selected @endif value="Cash">Cash
                                    </option>
                                    <option @if (old('payment_type') == 'Bank') selected @endif value="Bank">Bank
                                        Transfer</option>
                                    <option @if (old('payment_type') == 'Cheque') selected @endif value="Cheque">
                                        Cheque</option>
                                    <option @if (old('payment_type') == 'Card') selected @endif value="Card">
                                        Credit Card</option>
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
                                <input type="text" class="form-control" name="transaction_number"
                                    id="update_transaction_number" value="{{ old('transaction_number') }}"
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
                                <input type="text" class="form-control" name="bank_name" id="update_bank_name"
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
                                <input type="text" class="form-control" name="cheque_number"
                                    id="update_cheque_number" value="{{ old('cheque_number') }}"
                                    placeholder="Enter Cheque Number"
                                    data-parsley-required-message="The cheque number field is required." />
                                @error('cheque_number')
                                    <small class="red-text ml-10" role="alert">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <label for="donation_type">Donation Type</label>
                                <select name="donation_type" id="update_donation_type" class="select2 form-select"
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
                                <select name="payment_method" id="update_payment_method" class="select2 form-select"
                                    data-placeholder="Select Payment Term"
                                    data-parsley-errors-container="#payment_method_errors" required
                                    data-parsley-required-message="The payment method field is required.">
                                    <option value="">Select Payment Term</option>
                                    <option @if (old('payment_method') == 'Monthly') selected @endif value="Monthly">
                                        Monthly</option>
                                    <option @if (old('payment_method') == 'Quarterly') selected @endif value="Quarterly">
                                        Quarterly</option>
                                    <option @if (old('payment_method') == 'Half-Yearly') selected @endif value="Half-Yearly">
                                        Half Yearly</option>
                                    <option @if (old('payment_method') == 'Yearly') selected @endif value="Yearly">
                                        Yearly</option>
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
                                    id="update_remarks" placeholder="Enter Remarks" />
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
                        <button type="submit" class="btn primary_btn ">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Fee', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Fee', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/fees/fees.js') }}"></script>
@endsection
