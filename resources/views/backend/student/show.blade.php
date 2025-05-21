@extends('backend.layouts.app')
@section('title', 'View Student')
@section('styles')
    <style>
        h6 {
            margin: 10px 0;
        }

        .col-lg-4.col-md-4 label {
            margin-left: 8px;
            display: flex;
            align-items: center;
        }

        .card-body hr {
            margin-top: 15px;
            border: 1px solid #D8D8DD
        }

        .border-right {
            border-right: 1px solid #D8D8DD;
        }

        .border-left {
            border-left: 1px solid #D8D8DD;
        }

        .nav-item {
            margin: 8px 10px 0 0 !important;
        }

        .responsive_hr {
            display: none;
        }

        div.dataTables_wrapper div.col-sm-12 {
            padding: 0 !important;
        }

        .description-container {
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            white-space: normal;
        }

        .nav-pills .nav-link.active {
            background-color: #FFB42D;
        }

        .nav-link,
        .nav-link:focus,
        .nav-link:hover {
            color: #898989;
        }

        .dataTables_filter label input {
            width: auto !important;
        }

        @media screen and (max-width: 768px) {
            .data_table_wrap {
                overflow: scroll;
            }

            .border-right-md-none {
                border-right: none;
            }

            .border-right-md-block {
                border-right: 1px solid #D8D8DD;
            }
        }

        @media screen and (max-width: 425px) {
            .col-12 {
                display: flex;
                gap: 10px;
                padding: 8px;
            }

            .responsive_hr {
                display: block;
            }

            .border-right,
            .border-right-md-block {
                border: none;
            }

            h6 {
                margin: 3px 0;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card mb-2">
        <div class="card-header d-md-flex d-sm-block align-items-center justify-content-between py-md-2">
            <h5 class="card-title m-0 me-2 text-white d-none d-md-block">View Student Details</h5>
            <h3 class="card-title m-0 me-2 text-white d-block d-md-none">View Student Details</h3>

            <div class="d-flex gap-2 mt-4 mt-md-0">
                <button type="button" class="btn secondary_btn back">Back</button>
                <button type="button" class="btn secondary_btn edit" data-id="{{ $student->id }}">Update</button>
            </div>
        </div>
        <div class="card-body py-2">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Full Name</h6>
                    <label>{{ $student->full_name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Email</h6>
                    <label>{{ $student->email }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Mobile number</h6>
                    <label>{{ $student->phone }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Date of birth</h6>
                    <label>{{ date('d/m/Y', strtotime($student->dob)) }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Gender</h6>
                    <label>{{ $student->gender }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Country</h6>
                    <label>{{ $student->country->name ?? '-' }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12 border-right">
                    <h6>Village</h6>
                    <label>{{ $student->village->name }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <div class="col-lg-3 col-md-4 col-12 border-right-md-block">
                    <h6>Address</h6>
                    <label>{{ $student->address }}</label>
                </div>
                <hr class="my-1 d-block d-md-none d-lg-block">
                <div class="col-lg-3 col-md-4 col-12 border-right border-right-md-none">
                    <h6>Status</h6>
                    <label>{{ $student->status == 1 ? 'Enable' : 'Disable' }}</label>
                </div>
                <hr class="responsive_hr my-1">
                <hr class="my-1 d-none d-md-block d-lg-none">
                <div class="col-lg-3 col-md-4 col-12">
                    <h6>Is any illness</h6>
                    <label>{{ $student->is_any_illness == 1 ? 'Yes' : 'No' }}</label>
                </div>
                @if ($student->is_any_illness == 1)
                    <div class="col-lg-3 col-md-4 col-12 mb-md-0 border-left">
                        <h6>Illness Description</h6>
                        <label>{{ $student->illness_description }}</label>
                    </div>
                    <hr class="d-md-none">
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active mr-5" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-donation" aria-controls="navs-pills-top-donation"
                            aria-selected="true">
                            Donation
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link mr-5" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-admission" aria-controls="navs-pills-top-admission"
                            aria-selected="true">
                            Admission
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link mr-5" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-history" aria-controls="navs-pills-top-history"
                            aria-selected="true">
                            History
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navs-pills-top-donation" role="tabpanel">
                        <div class="table_content_wrapper">
                            <div class="data_table_wrap">
                                <table class="table" id="donation_table">
                                    <thead>
                                        <tr>
                                            <th>SR No.</th>
                                            <th>Slip No.</th>
                                            <th>Payment Type</th>
                                            <th>Payment Term</th>
                                            <th>Amount</th>
                                            <th>Paid At</th>
                                            <th>Admission Year</th>
                                            <th>Transaction Number</th>
                                            <th>Donation Type</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($donations as $key => $donation)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $donation->serial_number . '/' . $donation->financial_year }}
                                                </td>
                                                <td>{{ $donation->payment_type }}</td>
                                                <td>{{ $donation->payment_method }}</td>
                                                <td>{{ $donation->fees_amount }}</td>
                                                <td>{{ date('d/m/Y', strtotime($donation->paid_at)) }}</td>
                                                <td>{{ $donation->financial_year }}</td>
                                                <td>{{ $donation->transaction_number }}</td>
                                                <td>{{ $donation->donation_type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="navs-pills-top-admission" role="tabpanel">
                        <div class="table_content_wrapper">
                            <div class="data_table_wrap">
                                <table class="table" id="admission_table">
                                    <thead>
                                        <tr>
                                            <th>SR No.</th>
                                            <th>Year</th>
                                            <th>Date</th>
                                            <th>Institute Name</th>
                                            <th>course_id</th>
                                            <th>semester</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($donations as $key => $donation)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $donation->serial_number . '/' . $donation->financial_year }}
                                                </td>
                                                <td>{{ $donation->payment_type }}</td>
                                                <td>{{ $donation->payment_method }}</td>
                                                <td>{{ $donation->fees_amount }}</td>
                                                <td>{{ date('d/m/Y', strtotime($donation->paid_at)) }}</td>
                                                <td>{{ $donation->financial_year }}</td>
                                                <td>{{ $donation->transaction_number }}</td>
                                                <td>{{ $donation->donation_type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="navs-pills-top-history" role="tabpanel">
                        <div class="table_content_wrapper">
                            <div class="data_table_wrap">
                                <table class="table" id="history_table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>DateTime</th>
                                            <th>Action</th>
                                            <th>Module Name</th>
                                            <th>Action By</th>
                                            <th>Changed Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activities as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                                <td>{{ ucfirst($item->event) }}</td>
                                                <td>{{ $item->log_name }}</td>
                                                <td>{{ $item->user->name ?? '' }}</td>
                                                <td>
                                                    @if ($item->properties)
                                                        @php
                                                            $activityLog = json_decode($item->properties, true);

                                                            if (
                                                                isset($activityLog['old']) &&
                                                                isset($activityLog['attributes'])
                                                            ) {
                                                                $activityLog = json_decode($item->properties, true);
                                                                $oldData = $activityLog['old'] ?? [];
                                                                $newData = $activityLog['attributes'] ?? [];

                                                                $changedOld = '';
                                                                $changedNew = '';

                                                                // Show only changed keys
                                                                foreach ($newData as $key => $newValue) {
                                                                    $oldValue = $oldData[$key] ?? null;

                                                                    if ($newValue != $oldValue) {
                                                                        $changedNew .= $key . ' - ' . $newValue . ', ';
                                                                        $changedOld .= $key . ' - ' . $oldValue . ', ';
                                                                    }
                                                                }

                                                                if ($changedNew || $changedOld) {
                                                                    echo 'New:- ' . rtrim($changedNew, ', ') . '<br>';
                                                                    echo 'Old:- ' . rtrim($changedOld, ', ');
                                                                } elseif (!empty($newData)) {
                                                                    // fallback if only new data exists
                                                                    $attributesString = '';
                                                                    foreach ($newData as $key => $value) {
                                                                        $attributesString .=
                                                                            $key . ' - ' . $value . ', ';
                                                                    }
                                                                    echo rtrim($attributesString, ', ');
                                                                } elseif (!empty($oldData)) {
                                                                    // fallback if only old data exists
                                                                    $attributesString = '';
                                                                    foreach ($oldData as $key => $value) {
                                                                        $attributesString .=
                                                                            $key . ' - ' . $value . ', ';
                                                                    }
                                                                    echo rtrim($attributesString, ', ');
                                                                }
                                                            }
                                                            // Check if 'attributes' key exists
                                                            elseif (isset($activityLog['attributes'])) {
                                                                $attributesString = '';

                                                                // Iterate over each attribute and construct the string
                                                                foreach ($activityLog['attributes'] as $key => $value) {
                                                                    $attributesString .= $key . ' - ' . $value . ', ';
                                                                }

                                                                // Remove the trailing comma and space
                                                                $attributesString = rtrim($attributesString, ', ');

                                                                // echo instead of return
                                                                echo $attributesString;
                                                            } else {
                                                                $attributesString = '';

                                                                // Iterate over each attribute and construct the string
                                                                foreach ($activityLog['old'] as $key => $value) {
                                                                    $attributesString .= $key . ' - ' . $value . ', ';
                                                                }

                                                                // Remove the trailing comma and space
                                                                $attributesString = rtrim($attributesString, ', ');

                                                                // echo instead of return
                                                                echo $attributesString; // Handle case where 'attributes' is missing
                                                            }
                                                        @endphp
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#donation_table').DataTable();
            $('#admission_table').DataTable();
            $('#history_table').DataTable();
        });
    </script>
@endsection
