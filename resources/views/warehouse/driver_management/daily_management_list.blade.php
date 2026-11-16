@extends('warehouse.layouts.main')

@section('title', 'Driver Management')
@section('breadcrumb-item', 'Driver Management')
@section('breadcrumb-item-active', 'Driver Reports')

@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
    <style>
        .jconfirm-content {
            overflow: hidden !important;
        }
        .form-check-input:checked{
            background-color: #1e8449 !important;
            border-color: #1e8449 !important;
        }
        .form-switch .form-check-input{
            width: 4em !important;
            height: 2em !important;
            border: 1px solid #aaa !important;
        }
        
        .filter-card {
            background: rgba(16, 28, 76, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 15px;
        }
        
        .form-label {
            margin-bottom: 6px;
            color: #ffffff !important;
        }

        .table thead th,
        #staff-management thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background: linear-gradient(180deg, var(--desai-navy-deep, #101C4C), var(--desai-navy-light, #35429E)) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            vertical-align: middle !important;
        }

        /* Table body cells & all text in white */
        #staff-management tbody td,
        .table tbody td {
            background-color: rgba(16, 28, 76, 0.45) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            vertical-align: middle !important;
        }

        #staff-management.table-striped > tbody > tr:nth-of-type(odd) > *,
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }

        #staff-management tbody tr:hover > *,
        .table tbody tr:hover > * {
            background-color: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }

        #staff-management tbody td strong,
        #staff-management tbody td span:not(.badge),
        #staff-management tbody td div:not(.badge),
        #staff-management tbody td small,
        .table tbody td strong,
        .table tbody td span:not(.badge),
        .table tbody td div:not(.badge),
        .table tbody td small {
            color: #ffffff !important;
        }

        /* Card header & titles */
        .card-header h4,
        .card-header h4.text-dark,
        .card-header h4.text-white {
            color: #ffffff !important;
        }

        /* DataTables controls (length, filter, search, info, paginate) */
        #staff-management_wrapper,
        #staff-management_wrapper label,
        #staff-management_length,
        #staff-management_length label,
        #staff-management_filter,
        #staff-management_filter label,
        #staff-management_info,
        #staff-management_paginate,
        #staff-management_paginate .paginate_button,
        .dataTables_empty,
        .dataTables_processing {
            color: #ffffff !important;
        }

        #staff-management_filter input,
        #staff-management_length select {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }

        #staff-management_filter input::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        #staff-management_length select option {
            background-color: #101C4C !important;
            color: #ffffff !important;
        }

        #staff-management_paginate .paginate_button {
            color: #ffffff !important;
        }

        #staff-management_paginate .paginate_button.current,
        #staff-management_paginate .paginate_button.current:hover {
            color: #ffffff !important;
            background: linear-gradient(180deg, var(--desai-amber-light, #EE1A28), var(--desai-amber-deep, #EE1A28)) !important;
            border-color: #EE1A28 !important;
        }
        
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    {{-- Filter Card --}}
    <div class="card mb-4" style="background: rgba(16, 28, 76, 0.55); border: 1px solid rgba(255, 255, 255, 0.15);">
        <div class="card-body">
            <div class="row align-items-end g-3">
                <!-- Driver Filter -->
                <div class="col-md-3">
                    <label for="driver_id" class="form-label fw-semibold text-white">Driver</label>
                    <select name="driver_id" id="driver_id" class="form-select">
                        <option value="">All Drivers</option>
                        @forelse($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->name }}
                            </option>
                        @empty
                            <option value="" disabled>No drivers assigned to this warehouse</option>
                        @endforelse
                    </select>
                </div>
            
                <!-- From Date -->
                <div class="col-md-3">
                    <label for="from_date" class="form-label fw-semibold text-white">From Date</label>
                    <input type="date"
                           name="from_date"
                           id="from_date"
                           class="form-control">
                </div>
            
                <!-- To Date -->
                <div class="col-md-3">
                    <label for="to_date" class="form-label fw-semibold text-white">To Date</label>
                    <input type="date"
                           name="to_date"
                           id="to_date"
                           class="form-control">
                </div>
            
                <!-- Buttons -->
                <div class="col-md-3 d-flex">
                    <button type="button" id="filterBtn" class="btn btn-primary me-2 flex-grow-1"><i class="ti ti-filter me-1"></i> Filter</button>
                    <button type="button" id="resetBtn" class="btn btn-light border flex-grow-1"><i class="ti ti-rotate me-1"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error_msg'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-triangle-exclamation me-1"></i> {{ session('error_msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0 text-white fw-bold"><i class="fa fa-truck me-2"></i>Driver Product Report</h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('driver-product-create') }}" class="btn btn-success btn-sm">
                                <i class="ti ti-plus me-1"></i> Assign Products to Driver
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dt-responsive">
                        <table class="table table-striped table-bordered nowrap" id="staff-management">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">#</th>
                                    <th scope="col">Driver Name</th>
                                    <th scope="col">No of Products</th>
                                    <th scope="col">Total Quantity</th>
                                    <th scope="col">Assign Date</th>
                                    <th scope="col" style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>
    
<script>
    $(document).ready(function () {

    var role = getUrlParameter('role');

    var table = $('#staff-management').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: {
            url: '{{ route("get-driver-management-report-datatable") }}',

            data: function (d) {
                d.role = role;

                // Filter values
                d.driver_id = $('#driver_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },

        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'driver_name',
                name: 'driver_name'
            },
            {
                data: 'total_products',
                name: 'total_products'
            },
            {
                data: 'total_quantity',
                name: 'total_quantity',
                orderable: false,
                searchable: false
            },
            {
                data: 'assign_date',
                name: 'assign_date'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });


    // Apply filter
    $('#filterBtn').on('click', function () {

        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();

        // Validate date range
        if (fromDate && toDate && fromDate > toDate) {
            alert('From Date cannot be greater than To Date.');
            return;
        }

        table.ajax.reload();
    });


    // Reset filters
    $('#resetBtn').on('click', function () {

        $('#driver_id').val('');
        $('#from_date').val('');
        $('#to_date').val('');

        table.ajax.reload();
    });


    // Function to get URL parameters
    function getUrlParameter(sParam) {

        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {

            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined
                    ? true
                    : decodeURIComponent(sParameterName[1]);
            }
        }

        return false;
    }

});
</script>

    <!-- [Page Specific JS] end -->
@endsection
