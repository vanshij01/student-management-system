@extends('backend.layouts.app')
@section('title', 'Village Listing')
@section('styles')
    <style>
        div.dataTables_filter {
            flex: 1;
        }

        .dataTables_wrapper .dataTables_filter input {
            min-height: 50px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_length select {
            margin: 0 10px;
            min-height: 50px;
            width: 100px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
        }

        div.dt-processing>div:last-child>div {
            background: rgb(24 168 176);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            border: 1px solid #18a8b0;
            background: #18a8b0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:focus-visible {
            border: 1px solid #18a8b0;
        }

        div.dt-buttons>.dt-button {
            background-color: #FFB42D;
            color: #fff;
            border-color: #FFB42D;
        }

        .dashboard-header-container .add_btn {
            max-width: 225px;
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">Villages</h5>
            @php
                $chk = \App\Models\Permission::checkCRUDPermissionToUser('Village', 'create');
                if ($chk) {
                    echo "<a href='" .
                        route('village.create') .
                        "' class='btn btn-primary waves-effect waves-light text-white'><span class='d-md-none d-sm-inline-block'><i class='mdi mdi-plus me-sm-1'></i></span> <span class='d-none d-sm-inline-block'>Add Village</span></a>";
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
                <table class="datatables-basic table table-bordered table-striped" id="village_table">
                    <div id="overlay"></div>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Action</th>
                            <th>Sr. No.</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck" value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Village', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Village', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/village/village.js') }}"></script>
@endsection
