@extends('frontend.layouts.app')
@section('title', 'Complain Listing')
@section('styles')
    <style>
        .status-button {
            pointer-events: none;
            text-transform: capitalize;
            width: 135px;
        }

        @media (max-width: 767px) {
            .table-responsive {
                width: 100%;
                overflow: scroll;
                white-space: nowrap;
            }

            td.complain {
                width: 400px;
                white-space: break-spaces;
            }

            td.document {
                width: 400px;
                white-space: break-spaces;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">Complain</h5>

            <div class="d-flex gap-2">
                <a href={{ route('student.dashboard') }} class='btn btn-primary waves-effect waves-light text-white'><span
                        class='d-md-none d-sm-inline-block'><i class='mdi mdi-arrow-left me-sm-1'></i></span> <span
                        class='d-none d-sm-inline-block'>Back</span></a>
                <a href={{ route('student.complain.create') }}
                    class='btn btn-primary waves-effect waves-light text-white'><span class='d-md-none d-sm-inline-block'><i
                            class='mdi mdi-plus me-sm-1'></i></span> <span class='d-none d-sm-inline-block'>Add
                        Complain</span></a>
            </div>
        </div>

        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body pt-0">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table table-bordered table-striped" id="complain_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Complain Type</th>
                            <th>Complain Message</th>
                            <th>Complain Document</th>
                            <th>Complain Status</th>
                            <th>Complain Date</th>
                            <th>Admin Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complains as $key => $complain)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @switch($complain->type)
                                        @case(1)
                                            Technical
                                        @break

                                        @case(2)
                                            System
                                        @break

                                        @case(3)
                                            Management
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                <td class="complain">
                                    <p>{{ $complain->message }}</p>
                                </td>
                                @if (!empty($complain->document))
                                    <td class="document"> <a href="{{ asset($complain->document) }}"
                                            target="_blank">Complain</a></td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>
                                    @switch($complain->status)
                                        @case(1)
                                            <button class="btn btn-outline-warning status-button">Pending</button>
                                        @break

                                        @case(2)
                                            <button class="btn btn-outline-secondary status-button">Open</button>
                                        @break

                                        @case(3)
                                            <button class="btn btn-outline-info status-button">In Progress</button>
                                        @break

                                        @case(4)
                                            <button class="btn btn-outline-success status-button">Completed</button>
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                <td>{{ $complain->created_at->format('d/m/Y') }}</td>
                                <td>{{ $complain->admin_comment }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#complain_table').dataTable();

            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 3000);
        });
    </script>
@endsection
