@extends('manufacture.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')
@section('breadcrumb-item-active', 'Products')

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
        /* Table Header & Dark Background Styling */
        #staff-management thead th,
        .table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background-color: #0f172a !important;
            border-color: #334155 !important;
            vertical-align: middle !important;
            padding: 12px 14px !important;
        }
        #staff-management tbody td,
        .table tbody td {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border-color: #334155 !important;
            vertical-align: middle !important;
        }
        #staff-management.table-striped > tbody > tr:nth-of-type(odd) > *,
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #243247 !important;
            color: #ffffff !important;
        }
        #staff-management.table-hover > tbody > tr:hover > *,
        #staff-management tbody tr:hover > *,
        .table tbody tr:hover > * {
            background-color: #2e3e57 !important;
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
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">

                    <div class="col-lg-6">
                        <h4 class="mb-0 text-dark fw-bold">Assigned Products to Warehouses</h4>
                    </div>
                    <div class="col-lg-6">
                      
                        <a href="{{route('manufacture-product-create')}}" >
                            <button class="btn btn-secondary float-end me-2" tabindex="0" aria-controls="custom-btn" type="button">
                                <span>Assign New <i class="fa fa-plus" aria-hidden="true"></i></span>
                            </button>
                        </a>
                            
                    </div>
                </div>
              </div>
              <div class="card-body">

                        @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                <div class="table-responsive dt-responsive">
                  <table class="table table-striped table-bordered nowrap" id="staff-management">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">#</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Quantity (Case)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                      
                   </table>
                </div>
              </div>
            </div>
        </div>
          <!-- Multiple Table Control Elements end -->
          
          
    </div>
    <!-- [ Main Content ] end -->
      
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->

    <script src="{{ URL::asset('public/backend/assets/js/jquery-confirm.js') }}" type="text/javascript"></script>
    
    <script>
    $(document).ready(function () {
        var role = getUrlParameter('role'); // Get role from URL

        $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("manufacture-product-datatable") }}',
                data: function (d) {
                    d.role = role;    // Passing role
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'warehouse_name', name: 'warehouse_name'},
                {data: 'product_name', name: 'product_name'},
                {data: 'per_case_quantity', name: 'per_case_quantity'},
                {data: 'status', name: 'status'},
                {data: 'assign_date', name: 'assign_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
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
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        }
    });
</script>

    <!-- [Page Specific JS] end -->
@endsection
