@extends('warehouse.layouts.main')

@section('title', 'Stock Management')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Stocks')

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
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">

                    <div class="col-lg-6">
                        <h4 class="mb-0 text-white fw-bold"><i class="fa fa-boxes-stacked me-2"></i>Product Stock</h4>
                    </div>
                    <div class="col-lg-6">
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
                            <th scope="col">Product Name</th>
                            <th scope="col">Stock (Case)</th>
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
                url: '{{ route("warehouse-stock-datatable") }}',
                data: function (d) {
                    d.role = role;    // Passing role
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'product_name', name: 'product_name'},
                {data: 'stock_quantity', name: 'stock_quantity'}
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
