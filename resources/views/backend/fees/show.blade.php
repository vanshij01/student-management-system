@extends('backend.layouts.app')
@section('title', 'Receipt')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bootstrap4.css') }}" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        p {
            font-family: 'Montserrat', sans-serif;
        }

        h3 {
            font-family: 'Montserrat', sans-serif;
            line-height: 110%;
            margin: 1.46rem 0 1.168rem 0;
            color: #333;
        }

        h4 {
            font-size: 2.28rem;
            line-height: 110%;
            margin: 1.14rem 0 .912rem 0;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            line-height: 1.1;
            color: #333;
        }

        /* .card {
                            border-radius: 0;
                            padding: 10px;
                        } */

        .print_field {
            display: inline-block;
            border-bottom: 1px solid #212529;
            text-align: left;
            font-weight: 600;
            margin-left: 5px;
            min-height: 20px;
        }

        .new_print table tr th,
        .new_print table tr td {
            border: 1px solid;
            text-align: center;
            /* padding: 10px; */
        }

        .donation_details {
            width: 100%;
            min-height: 60px;
        }

        .ruppes_box {
            border: 2px solid;
            border-radius: 35px;
            padding: 10px 15px;
            width: 145px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .support {
            padding-top: 8px;
        }

        .payment_recieved p.recevier_name {
            margin-top: 20px;
            font-weight: 600;
        }

        footer {
            display: none;
        }

        .payment_recieved p {
            line-height: 1;
            margin-top: 30px;
        }

        .ruppes_box .amount {
            margin-left: 10px;
            font-weight: bold;
            font-size: 15px;
        }

        .new_print+.new_print {
            margin-top: 20px;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }

        .support.donor_signature .print_field {
            width: 200px;
            display: block;
            margin: 0 auto;
        }

        .col-print-10 {
            width: 90%;
            float: left;
        }

        .col-print-2 {
            width: 10%;
            float: left;
            display: flex;
            align-items: center;
        }

        .btn-primary {
            color: #fff;
            background-color: #ffb422;
            border-color: #ffb422;
        }

        .row-style {
            margin-top: 20px;
            font-size: 16px;
        }

        .row-style-last {
            margin-top: 72px;
            margin-bottom: 72px;
            font-size: 16px;
        }

        .font_16 {
            font-size: 16px;
        }

        .logo div img {
            max-height: 150px;
        }

        .parsley-errors-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        @media print {

            @page {
                margin: 0px !important;
                padding: 0px !important;
            }

            .card {
                margin-top: 10px;
                border: none;
            }

            #print_div {
                border: 1px dashed #ccc;
                margin: 0px 15px;
            }

            .col-print-2 {
                padding-left: 15px;
                display: flex;
                align-items: center;
            }

            h4 {
                font-size: 25px;
            }

            .admin-sidebar{
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    @if (Session::has('success'))
        <p class="alert alert-success">{{ Session::get('success') }}</p>
    @endif
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
                                <label for="admission_id">Student</label>
                                <input type="hidden" name="admission_id" id="admission_id_input">
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
                                <label for="fees_amount">Amount</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" step="any" min="0"
                                        name="fees_amount" id="update_fees_amount" value="{{ old('fees_amount') }}"
                                        placeholder="Enter Amount" data-parsley-errors-container="#fees_amount_errors"
                                        required readonly data-parsley-required-message="The amount field is required." />
                                    @error('fees_amount')
                                        <small class="red-text ml-10" role="alert"
                                            style="position: absolute; margin-left: -25px;">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                    <span class="amount_error d-none" style="color: red;">Cash Not Allowed Above
                                        ₹20,000</span>
                                </div>
                                <div id="fees_amount_errors"></div>
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
                                    <option @if (old('payment_type') == 'Cheque') selected @endif value="Cheque">Cheque
                                    </option>
                                    <option @if (old('payment_type') == 'Card') selected @endif value="Card">Credit
                                        Card</option>
                                    <option @if (old('payment_type') == 'E-Wallet') selected @endif value="E-Wallet">E-Wallet
                                    </option>
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

    <div class="card pb-0">
        <div class="card-header bg-white d-print-none">
            <h2>Donation
                <div class="float-right ml-2">
                    <a class="btn btn-primary p-2" id="print_update_fees" data-id="{{ $fees->id }}"><i
                            class="las la-edit text-white"></i></a>
                    <a class="btn btn-primary" href="{{ url('fees') }}"><i class="fa fa-list mr-1"></i>
                        Donation List</a>
                    <a class="btn btn-primary p-2" id="print_recepit" onclick="javascript:window.print()"><i
                            class="las la-print text-white"></i></a>
                </div>
            </h2>
        </div>

        <div class="card-body border:none pb-0" id="print_div">
            <div class="new_print">
                <div class="row mt-5">
                    <div class="col-print-2">
                        <div class="logo">
                            <div class="text-right">
                                <img src="{{ asset('assets/images/sklps-icon.png') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-print-10">
                        <div class="text-center">
                            <p class="mb-0 font_16">Trust Regi. A-3336 | PAN No:
                                AAATS6173J</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 30px;">Shree Kutchi Leva
                                Patel Samaj - Ahmedabad</h3>
                            <h4 class="my-2">Smt. Shantaben Bhikhalal Shiyani Atithi Bhavan</h4>
                            <p class="font_16 lh-sm">
                                Near Loyla Hall Opp. Kamnath Mahadev, Saint Xaviers School Road, <br>
                                Naranpura-Ahmedabad-380013, Mobile : +91 9099211718, (079) 27911718 <br>
                                E-mail: sklpsahmedabad@rediffmail.com, www.sklpsahmedabad.com
                            </p>
                            <p>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m6">
                        <div>
                            Slip No: <span class="print_field"
                                style="width: 200px; font-size: 19px;">{{ $fees->serial_number . ' /' . $fees->financial_year }}</span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="float: right;">
                            Date: <span class="print_field"
                                style="width: 200px; font-size: 17px;">{{ date('d-m-Y', strtotime($fees->created_at)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        <div class="font_16">
                            Shri/Smt:<span class="print_field" style="width: calc(100% - 78px); font-size: 19px;">
                                {{ $fees->student_name ?? $fees->father_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        <div class="font_16">
                            Address: <span class="print_field" style="width: calc(100% - 78px); font-size: 17px;">
                                {{ $fees->address }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        Contributed the sum of RS. <span class="print_field text-center"
                            style="width: calc(30% - 72px); font-size: 17px;">{{ number_format($fees->fees_amount, 2) }}</span>
                        to
                        @if ($fees->donation_type == 'Vidhyadan')
                            Vidhyadan
                        @else
                            {{ $fees->donation_type }}
                        @endif
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m6">
                        Paid By: <span class="print_field" style="width: calc(34% - 45px);">
                            @if (!empty($fees->payment_type) && isset($fees->payment_type))
                                {{ $fees->payment_type }}
                            @else
                                -
                            @endif
                        </span>
                        @if (!empty($fees->payment_type) && isset($fees->payment_type) && $fees->payment_type == 'Cheque')
                            Bank Name: <span class="print_field"
                                style="width: calc(33% - 106px);">{{ $fees->bank_name }}</span>
                            Number: <span class="print_field"
                                style="width: calc(33% - 110px);">{{ $fees->cheque_number }}</span>
                        @elseif(
                            !empty($fees->payment_type) &&
                                isset($fees->payment_type) &&
                                ( $fees->payment_type == 'E-wallet' || $fees->payment_type == 'Bank' || $fees->payment_type == 'Card'))
                            Bank Name: <span class="print_field text-center" style="width: calc(33% - 106px);">
                                {{ $fees->bank_name }}</span>
                            Number: <span class="print_field"
                                style="width: calc(33% - 110px);">{{ $fees->transaction_number }}</span>
                        @else
                            Bank Name: <span class="print_field text-center" style="width: calc(33% - 106px);"> -
                            </span>
                            Number: <span class="print_field text-center" style="width: calc(33% - 110px);"> -
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        in words: <span class="print_field" style="width: calc(100% - 80px);">{{ $amountInWords }}</span>
                        </span>
                    </div>
                </div>
                <div class="row row-style-last">
                    <div class="col m4 text-left">
                        <div class="ruppes_box">
                            <img src="http://guest.sklpsahmedabad.com/assets/rs.png" width="15">
                            <span class="amount"
                                style="font-size: 20px;">{{ number_format($fees->fees_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="col m4 text-center">
                        <div class="support">
                            <strong>Thanks for Support</strong>
                        </div>
                    </div>

                    <div class="col m4">
                        <div class="payment_recieved float-right">
                            <p class="recevier_name mb-0">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="mt-0">Payment Received</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="new_print">
                <div class="row mt-5">
                    <div class="col-print-2">
                        <div class="logo">
                            <img src="{{ asset('assets/images/sklps-icon.png') }}">
                        </div>
                    </div>
                    <div class="col-print-10">
                        <div class="text-center">
                            <p class="mb-0 font_16">Trust Regi. A-3336 | PAN No:
                                AAATS6173J</p>
                            <h3 class="mb-0 fw-bold" style="font-size: 30px;">Shree Kutchi Leva
                                Patel Samaj - Ahmedabad</h3>
                            <h4 class="my-2">Smt. Shantaben Bhikhalal Shiyani Atithi Bhavan</h4>
                            <p class="font_16 lh-sm">
                                Near Loyla Hall Opp. Kamnath Mahadev, Saint Xaviers School Road, <br>
                                Naranpura-Ahmedabad-380013, Mobile : +91 9099211718, (079) 27911718 <br>
                                E-mail: sklpsahmedabad@rediffmail.com, www.sklpsahmedabad.com
                            </p>
                            <p>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m6">
                        <div>
                            Slip No: <span class="print_field"
                                style="width: 200px; font-size: 19px;">{{ $fees->serial_number . ' /' . $fees->financial_year }}</span>
                        </div>
                    </div>
                    <div class="col m6">
                        <div style="float: right;">
                            Date: <span class="print_field"
                                style="width: 200px; font-size: 17px;">{{ date('d-m-Y', strtotime($fees->created_at)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        <div class="font_16">
                            Shri/Smt:<span class="print_field" style="width: calc(100% - 78px); font-size: 19px;">
                                {{ $fees->student_name ?? $fees->father_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        <div class="font_16">
                            Address: <span class="print_field" style="width: calc(100% - 78px); font-size: 17px;">
                                {{ $fees->address }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        Contributed the sum of RS. <span class="print_field text-center"
                            style="width: calc(30% - 72px); font-size: 17px;">{{ number_format($fees->fees_amount, 2) }}</span>
                        to
                        @if ($fees->donation_type == 'Vidhyadan')
                            Vidhyadan
                        @else
                            {{ $fees->donation_type }}
                        @endif
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m6">
                        Paid By: <span class="print_field" style="width: calc(34% - 45px);">
                            @if (!empty($fees->payment_type) && isset($fees->payment_type))
                                {{ $fees->payment_type }}
                            @else
                                -
                            @endif
                        </span>
                        @if (!empty($fees->payment_type) && isset($fees->payment_type) && $fees->payment_type == 'Cheque')
                            Bank Name: <span class="print_field"
                                style="width: calc(33% - 106px);">{{ $fees->bank_name }}</span>
                            Number: <span class="print_field"
                                style="width: calc(33% - 110px);">{{ $fees->cheque_number }}</span>
                        @elseif(
                            !empty($fees->payment_type) &&
                                isset($fees->payment_type) &&
                                ($fees->payment_type == 'Card' || $fees->payment_type == 'E-wallet'))
                            Bank Name: <span class="print_field text-center" style="width: calc(33% - 106px);">
                                -</span>
                            Number: <span class="print_field"
                                style="width: calc(33% - 110px);">{{ $fees->transaction_number }}</span>
                        @else
                            Bank Name: <span class="print_field text-center" style="width: calc(33% - 106px);"> -
                            </span>
                            Number: <span class="print_field text-center" style="width: calc(33% - 110px);"> -
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row row-style">
                    <div class="col m12">
                        in words: <span class="print_field" style="width: calc(100% - 80px);">{{ $amountInWords }}</span>
                    </div>
                </div>
                <div class="row row-style-last">
                    <div class="col m4 text-left">
                        <div class="ruppes_box">
                            <img src="http://guest.sklpsahmedabad.com/assets/rs.png" width="15">
                            <span class="amount"
                                style="font-size: 20px;">{{ number_format($fees->fees_amount, 2) }}</span>
                        </div>
                    </div>
                    <div class="col m4 text-center">
                        <div class="support donor_signature">
                            <span class="print_field" style="font-size: 17px;"></span>
                            Donor Signature
                        </div>
                    </div>

                    <div class="col m4">
                        <div class="payment_recieved float-right">
                            <p class="recevier_name mb-0">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="mt-0">Payment Received</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/fees/fees.js') }}"></script>
@endsection
