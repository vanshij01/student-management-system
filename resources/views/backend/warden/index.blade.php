@extends('backend.layouts.app')
@section('title', 'Warden Listing')
@section('styles')
<style>
    .dataTables_scrollBody{
        overflow: unset !important;
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
            <h3 class="dashboard-header">Wardens</h3>
        </div>

        @php
            $chk = \App\Models\Permission::checkCRUDPermissionToUser('Warden', 'create');

            if ($chk) {
                echo "<a href='" . route('warden.create') . "' class='btn primary_btn add_btn'>Add</a>";
            }
        @endphp

    </div>
    <div class="table_content_wrapper">
        <div class="data_table_wrap">
            <table id="warden_table" class="table table-bordered align-middle" style="width: 100%">
                <thead class="table-light">
                    <tr>
                        <th></th>
                            <th>Action</th>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" id="isSuperAdmin" value="{{ \App\Models\Permission::isSuperAdmin() }}">
    <input type="hidden" id="readCheck" value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Warden', 'read') }}">
    <input type="hidden" id="updateCheck"
        value="{{ \App\Models\Permission::checkCRUDPermissionToUser('Warden', 'update') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/warden/warden.js') }}"></script>
@endsection
