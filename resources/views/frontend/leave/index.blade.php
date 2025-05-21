@extends('frontend.layouts.app')
@section('title', 'Leave Listing')
@section('styles')
    <style>
        .table tbody tr.odd {
            background-color: #EAF4FF;
        }

        table thead tr th {
            font-weight: 600;
        }

        .addButton {
            padding-top: 13px;
            padding-bottom: 13px;
        }
    </style>
@endsection
@section('content')
    <section class="Personal_details_form">
        <div class="container">
            <a href="#" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back</a><a href={{ route('student.dashboard') }}
                class='btn btn-primary waves-effect waves-light text-white'><span class='d-md-none d-sm-inline-block'><i
                        class='mdi mdi-arrow-left me-sm-1'></i></span> <span
                    class='d-none d-sm-inline-block'>Back</span></a>
            <a href={{ route('student.leave.create') }} class='btn btn-primary waves-effect waves-light text-white'><span
                    class='d-md-none d-sm-inline-block'><i class='mdi mdi-plus me-sm-1'></i></span> <span
                    class='d-none d-sm-inline-block'>Add
                    Leave</span></a>

            <div class="text-center com-space ">
                <h2 class="admission_form_title">Leave Application</h2>
            </div>
            @if (session('message'))
                <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                    <strong>{{ session('message') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <table class="datatables-basic table table-bordered table-striped" id="leave_table">
                <div id="overlay"></div>
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Leave Subject</th>
                        <th>Leave From</th>
                        <th>Leave To</th>
                        <th>Approved By</th>
                        <th>Leave Status</th>
                        <th>Leave Reason</th>
                        <th>Leave Ticket</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $key => $leave)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $leave->subject ?? '' }}</td>
                            <td>{{ date('d/m/Y', strtotime($leave->leave_from)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($leave->leave_to)) }}</td>
                            <td>{{ $leave->approve_by }}</td>
                            <td>{{ $leave->leave_status }}</td>
                            <td>{{ $leave->reason }}</td>
                            @if (!empty($leave->ticket))
                                <td class="ticket"> <a href="{{ asset($leave->ticket) }}"
                                        target="_blank">Ticket</a></td>
                            @else
                                <td>-</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#leave_table').dataTable();

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
