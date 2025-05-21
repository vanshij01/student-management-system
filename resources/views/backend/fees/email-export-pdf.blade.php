<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap4.css') }}" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        @media screen and (min-width: 768px) {

            .card .reservations-list-search form.form-inline input,
            .card .reports-list-search form.form-inline input {
                max-width: 118px;
            }
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .print_field {
            display: inline-block;
            border-bottom: 1px solid #393939;
            text-align: left;
            /* font-weight: 600; */
            margin-left: 0 !important;
            vertical-align: top;
            min-height: 20px;
            font-size: 17px;
            margin-left: 6px !important;
            color: #000;
        }

        .form-block {
            font-size: 16px;
            /* font-weight: 300; */
            color: #3b3b3b;
            /* font-weight: lighter; */
        }

        @page {
            /* size: 920px 735px; */
            size: 21.0cm 14.85cm;
            margin: 20px;
        }

        /* @page {
            size: 21cm 29.7cm;
            margin: 0;
        } */

        .card {
            border: 1px dashed #ccc;
        }

        .tab-content {
            padding: 0px !important;
            margin: 0px !important;
        }

        .row-style {
            margin-top: 8px;
            font-size: 16px;
        }

        .row-style-last {
            margin: 30px 0px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .ruppes_box {
            border: 2px solid;
            border-radius: 35px;
            padding: 8px 10px;
            width: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .support.donor_signature .print_field {
            width: 200px;
            display: flex;
            left: -45px;
            position: relative;
        }
    </style>
</head>

<body>
    {{-- <div class="container-xxl flex-grow-1 container-p-y p-0"> --}}
    <div class="card p-0 m-0">
        <div class="card-body p-0 m-0">
            <div class="tab-content">
                <div class="row header">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block" style="width: 20%">
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/sklps-icon.png'))) }}"
                                    style="max-height: 150px">
                            </td>
                            <td class="form-block" style="width: 80%; text-align: center;">
                                <p style="margin-bottom: 0px; font-size: 16px;">Trust Regi. A-3336 | PAN No:
                                    AAATS6173J</p>
                                <h3 style="font-size: 25px; margin: 5px auto;">Shree Kutchi Leva
                                    Patel Samaj - Ahmedabad</h3>
                                <h4 style="margin: 0px auto;">Smt. Shantaben Bhikhalal Shiyani Atithi Bhavan</h4>
                                <p class="font-size: 16px; lh-sm">
                                    Near Loyla Hall Opp. Kamnath Mahadev, Saint Xaviers School Road, <br>
                                    Naranpura-Ahmedabad-380013, Mobile : +91 9099211718, (079) 27911718 <br>
                                    E-mail: sklpsahmedabad@rediffmail.com, www.sklpsahmedabad.com
                                </p>
                                <p>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <div class="col-md-2" style="text-align: center;">

                    </div>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block" style="width: 33.33%">
                                Slip No: <span class="print_field"
                                    style="width: 60%; ">{{ $fees->serial_number . ' /' . $fees->financial_year }}</span>
                            </td>
                            <td class="form-block" style="width: 30%">
                            </td>
                            <td class="form-block" style="width: 36.66%">
                                Date: <span class="print_field"
                                    style="width: 81%; ">{{ date('d-m-Y', strtotime($fees->created_at)) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block">
                                Shri/Smt: <span class="print_field"
                                    style="width: 89.6%; ">{{ $fees->student_name ?? $fees->father_name }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block">
                                Address: <span class="print_field" style="width: 90%;"> {{ $fees->address }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            </td>
                            <td class="form-block">
                                Contributed the sum of RS. <span class="print_field" style="width: 57%;">
                                    {{ number_format($fees->fees_amount, 2) }}</span>
                                to
                                @if ($fees->donation_type == 'Vidhyadan')
                                    Vidhyadan
                                @else
                                    {{ $fees->donation_type }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block" style="width: 33.33%">
                                Paid By: <span class="print_field" style="width: 60%; ">
                                    @if (!empty($fees->payment_type) && isset($fees->payment_type))
                                        {{ $fees->payment_type }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                            @if (!empty($fees->payment_type) && isset($fees->payment_type) && $fees->payment_type == 'Cheque')
                                <td class="form-block" style="width: 33.33%">
                                    Bank Name: <span class="print_field"
                                        style="width: 57%; ">{{ $fees->bank_name }}</span>
                                </td>
                                <td class="form-block" style="width: 33.33%">
                                    Number: <span class="print_field"
                                        style="width: 70%; ">{{ $fees->cheque_number }}</span>
                                </td>
                            @elseif(
                                !empty($fees->payment_type) &&
                                    isset($fees->payment_type) &&
                                    ($fees->payment_type == 'Card' || $fees->payment_type == 'E-wallet'))
                                <td class="form-block" style="width: 33.33%">
                                    Bank Name: <span class="print_field" style="width: 57%; ">-</span>
                                </td>
                                <td class="form-block" style="width: 33.33%">
                                    Number: <span class="print_field"
                                        style="width: 70%; ">{{ $fees->transaction_number }}</span>
                                </td>
                            @else
                                <td class="form-block" style="width: 33.33%">
                                    Bank Name: <span class="print_field" style="width: 57%;">-</span>
                                </td>
                                <td class="form-block" style="width: 33.33%">
                                    Number: <span class="print_field" style="width: 70%;">-</span>
                                </td>
                            @endif
                        </tr>
                    </table>
                </div>
                <div class="row row-style">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block">
                                In Words: <span class="print_field" style="width: 89%;">
                                    {{ $amountInWords }}/-
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row row-style-last">
                    <table style="width: 100%;">
                        <tr>
                            <td class="form-block" style="width: 33.33%">
                                <span style="width: 60%; ">
                                    <div class="ruppes_box">
                                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/rs.png'))) }}"
                                            width="8">
                                        <span class="amount"
                                            style="font-size: 17px;">{{ number_format($fees->fees_amount, 2) }}</span>
                                    </div>
                            </td>
                            <td class="form-block" style="width: 30%">
                                <strong style="margin-left: -30px">Thanks for Support</strong>
                                {{-- <div class="support donor_signature">
                                    <span class="print_field" style="font-size: 17px;"></span>
                                    Donor Signature
                                </div> --}}
                            </td>
                            <td class="form-block" style="width: 15%">
                                <span style="width: 82%;"><strong>{{ auth()->user()->name }}</strong> <br>
                                    Payment
                                    Received</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
</body>

</html>
