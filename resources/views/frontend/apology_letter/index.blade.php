@extends('frontend.layouts.app')
@section('title', 'Apology Letter Listing')
@section('styles')
    <style>
        .table tbody tr.odd {
            background-color: #EAF4FF;
        }

        table thead tr th {
            font-weight: 600;
        }

        .dataTables_processing {
            z-index: 99999;
        }

        .light-style .swal2-container {
            z-index: 99999;
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

        div.dataTables_wrapper div.col-sm-12 {
            padding: 0 !important;
        }

        @media screen and (max-width: 425px) {
            .modal-dialog {
                display: flex;
                align-items: center;
                min-height: calc(100% - var(--bs-modal-margin)* 2);
            }
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">Apology Letter</h5>

            <div class="d-flex gap-2">
                <a href={{ route('student.dashboard') }} class='btn btn-primary waves-effect waves-light text-white'><span
                        class='d-md-none d-sm-inline-block'><i class='mdi mdi-arrow-left me-sm-1'></i></span> <span
                        class='d-none d-sm-inline-block'>Back</span></a>
                <a href={{ route('student.apology_letter.create') }}
                    class='btn btn-primary waves-effect waves-light text-white'><span class='d-md-none d-sm-inline-block'><i
                            class='mdi mdi-plus me-sm-1'></i></span> <span class='d-none d-sm-inline-block'>Add
                        Apology Letter</span></a>
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
                <table class="datatables-basic table table-bordered table-striped" id="apology_letter_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Subject</th>
                            <th>Content</th>
                            <th>File</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($letters as $key => $letter)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $letter->subject }}</td>
                                <td>{{ $letter->letter_content }}</td>
                                @if (!empty($letter->document))
                                    <td> <a href="{{ asset($letter->document) }}" target="_blank">Apology
                                            Latter</a></td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>{{ $letter->note }}</td>
                                <td>{{ $letter->created_at->format('d/m/Y') }}</td>
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
            $('#apology_letter_table').dataTable();
        });
    </script>
@endsection
