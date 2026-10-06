@extends('warehouse.layouts.main')

@section('title', 'Driver Management')
@section('breadcrumb-item', 'Driver Management')
@section('breadcrumb-item-active', 'Driver Reports')

@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset('public/backend/assets/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css" />
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
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }
        
        .form-label {
            margin-bottom: 6px;
            color: #374151;
        }
        
        #resetBtn {
            height: 38px;
        }
        
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    <div class="row mb-4">
    <!-- Driver Filter -->
        <div class="col-md-3">
            <label for="driver_id" class="form-label fw-semibold">Driver</label>
            <select name="driver_id" id="driver_id" class="form-select">
                <option value="">All Drivers</option>
    
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
        </div>
    
        <!-- From Date -->
        <div class="col-md-3">
            <label for="from_date" class="form-label fw-semibold">From Date</label>
            <input type="date"
                   name="from_date"
                   id="from_date"
                   class="form-control">
        </div>
    
        <!-- To Date -->
        <div class="col-md-3">
            <label for="to_date" class="form-label fw-semibold">To Date</label>
            <input type="date"
                   name="to_date"
                   id="to_date"
                   class="form-control">
        </div>
    
        <!-- Buttons -->
        <div class="col-md-3 d-flex align-items-end">
            <button type="button" id="filterBtn" class="btn btn-primary m-2">Filter</button>
            <button type="button" id="resetBtn" class="btn btn-light border m-2">Reset</button>
        </div>
    </div>

    <hr class="text-white"><hr class="text-white">
    <!-- Table -->
    <div class="table-responsive dt-responsive">
        <table class="table table-striped table-bordered nowrap" id="staff-management">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Driver Name</th>
                    <th scope="col">No of Product</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
      
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->

<script src="{{ URL::asset('public/backend/assets/js/jquery-confirm.js') }}" type="text/javascript"></script>
    
    
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