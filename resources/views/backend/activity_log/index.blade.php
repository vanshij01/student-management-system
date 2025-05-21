@extends('backend.layouts.app')
@section('title', 'Activity Log')
@section('styles')
    <style>
        div.dataTables_length {
            margin-left: -68px;
        }

        div.dataTables_filter {
            flex: 1;
        }

        .dataTables_wrapper .dataTables_filter label {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            min-height: 50px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
            width: 100%;
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

        /* .dataTables_scrollBody {
                                                                                                                                    overflow-x: scroll !important;
                                                                                                                                    overflow-y: hidden !important;
                                                                                                                                    position: static !important;
                                                                                                                                } */

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

        .sk-primary {
            position: absolute;
            left: 45%;
            top: 50%;
        }

        .card-datatable {
            min-height: 200px;
            /* overflow: hidden; */
        }

        input[type="search"]::-webkit-search-decoration,
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-results-button,
        input[type="search"]::-webkit-search-results-decoration {
            -webkit-appearance: none;
        }

        select#selPagesUp {
            /* position: absolute; */
            width: auto;
            top: 0px;
            z-index: 9;
        }

        select#selPagesBottom {
            position: relative;
            width: auto;
            /* top: 115px; */
            z-index: 9;
        }

        .disabled>.page-link {
            background-color: transparent;
        }

        .filter-card

        /* .card-body */
            {
            background: #A29678;
        }

        .form-floating-outline label::after .filter_label {
            background: #A29678;
        }

        .find_address {
            width: 100%;
            height: 46px;
        }

        select#selPagesUp {
            margin: 0 10px;
            min-height: 50px;
            width: 100px;
            border-radius: 10px;
            border: 1px solid #1D1D1B33;
            padding: 4px;
            top: 20px;
        }

        .page_wrapper {
            display: flex;
            align-items: center;
            position: absolute;
        }

        #donation_table_filter {
            margin-bottom: 1rem;
        }

        .select2-container--open .select2-dropdown--below {
            z-index: 9999;
        }

        a.page-link {
            color: #666 !important;
            border: none;
        }

        .active>.page-link,
        .page-link.active,
        .page-link:hover {
            background-color: #18a8b0;
            border-color: #18a8b0;
            color: #fff !important;
        }

        .disabled>.page-link {
            border: none;
        }

        div.dataTables_processing>div:last-child>div {
            background-color: #18a8b0;
        }

        .data-table-header {
            width: calc(100% - 130px);
            position: relative;
            left: 130px;
        }

        .dt-buttons {
            position: relative !important;
            bottom: 5px;
        }

        div.dt-buttons>.dt-button {
            background: linear-gradient(to bottom, rgb(255 180 45) 0%, rgb(255 180 45) 100%);
            border: 1px solid rgb(255 180 45);
            color: #fff;
        }

        #donation_table_wrapper .data-table-header {
            display: grid !important;
            grid-template-columns: 88% 10%;
        }

        #donation_table_wrapper .dt-buttons {
            text-align: right;
        }

        #donation_table_wrapper div#donation_table_filter {
            margin: auto;
        }

        table.dataTable>thead>tr>th {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            color: #636578;
            font-weight: 500px;
            background-color: #F8F9FA;
        }

        .status-button {
            width: 110px;
            pointer-events: none;
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

        @media screen and (max-width: 1024px) {
            #donation_table_wrapper .data-table-header {
                grid-template-columns: 74% 24%;
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
                background: #18a8b0 !important;
            }

            .mobile-pagination .pagination {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            select#selPagesBottom {
                top: 36px;
                right: 41px;
            }
        }

        @media screen and (max-width: 425px) {
            .divBottom {
                display: block;
            }

            select#selPagesBottom {
                top: 0px;
                left: 130px;
            }

            select#selPagesUp {
                margin: 13px auto 0;
            }
        }

        /* Overlay styles */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* semi-transparent black */
            z-index: 9999;
            display: block;
            /* Initially hidden */
            overflow-x: hidden !important;
        }

        .pac-container {
            z-index: 99999 !important;
        }
    </style>
@endsection
@section('content')
    <form id="filter_form">
        <div class="admission-filter-bar">
            <input type="hidden" name="page_length" id="page_length" value="{{ $request->page_length }}">
            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="from" name="from"
                value="{{ $request->from }}" />
            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="to" name="to"
                value="{{ $request->to }}" />
            <select class="form-select select2" id="moduleId" name="moduleId" data-placeholder="Select Module">
                <option value="" selected>Select Module</option>
                @foreach ($modules as $module)
                    <option value="{{ $module }}" @if ($request->moduleId == $module) selected @endif>
                        {{ $module }}</option>
                @endforeach
            </select>
            <select class="form-select select2" name="actionId" id="actionId" data-placeholder="Select Action">
                <option value="" selected>Select Action</option>
                @foreach ($actions as $action)
                    <option value="{{ $action }}" @if ($request->actionId == $action) selected @endif>
                        {{ ucfirst($action) }}</option>
                @endforeach
            </select>
            <button class="btn primary_btn" type="submit" id="filter" name="filter">Filter</button>
            <button class="btn secondary_btn" type="submit" name="reset" id="reset">Reset</button>
        </div>
    </form>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
            <h5 class="card-title m-0 me-2 text-white">Activity Log</h5>
        </div>

        <div class="table_content_wrapper">
            <div class="data_table_wrap">
                <div class="row page-wrapper">
                    <div class="col-md-2 page_wrapper">
                        Show
                        <select id="selPagesUp" class="selPages form-select p-2">
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
                        entries
                    </div>
                </div>
                <table class="datatables-basic table table-bordered" id="donation_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Sr No.</th>
                            <th>DateTime</th>
                            <th>Action</th>
                            <th>Module Name</th>
                            <th>Action By</th>
                            <th>Student</th>
                            <th>Changed Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allData as $key => $item)
                            <tr>
                                <td></td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ ucfirst($item->event) }}</td>
                                <td>{{ $item->log_name }}</td>
                                <td>{{ $item->user->name ?? '' }}</td>
                                <td>{{ $item->student_id ?? '-' }}</td>
                                <td>
                                    @if ($item->properties)
                                        @php
                                            $activityLog = json_decode($item->properties, true);

                                            if (isset($activityLog['old']) && isset($activityLog['attributes'])) {
                                                /* // dd($activityLog);
                                                $attributesString = '';
                                                $oldsString = '';
                                                // Iterate over each attribute and construct the string
                                                foreach ($activityLog['attributes'] as $key => $value) {
                                                    $attributesString .= $key . ' - ' . $value . ', ';
                                                }

                                                // Remove the trailing comma and space
                                                $attributesString = 'New:- ' . rtrim($attributesString, ', ');

                                                // Iterate over each attribute and construct the string
                                                foreach ($activityLog['old'] as $key => $value) {
                                                    $oldsString .= $key . ' - ' . $value . ', ';
                                                }

                                                // Remove the trailing comma and space
                                                $oldsString = 'Old:- ' . rtrim($oldsString, ', ');

                                                // echo instead of return
                                                echo $attributesString . '<br>' . $oldsString; */
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
                                                        $attributesString .= $key . ' - ' . $value . ', ';
                                                    }
                                                    echo rtrim($attributesString, ', ');
                                                } elseif (!empty($oldData)) {
                                                    // fallback if only old data exists
                                                    $attributesString = '';
                                                    foreach ($oldData as $key => $value) {
                                                        $attributesString .= $key . ' - ' . $value . ', ';
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
                <div class="row page-wrapper divBottom pt-2">
                    <div class="col col-md-12">
                        {!! $allData->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/activity/activity.js') }}"></script>
@endsection
