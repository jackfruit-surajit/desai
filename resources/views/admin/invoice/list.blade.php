@extends('admin.layouts.main')

@section('title', 'Invoice Management')
@section('breadcrumb-item', 'Invoice Management')
@section('breadcrumb-item-active', 'Invoices')

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
        
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">

                    <div class="col-lg-4">
                        <h4 class="mb-0">Invoice List</h4>
                    </div>
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-end align-items-center">
                            <label for="from_date" class="me-2 mb-0 text-light">From:</label>
                            <input type="date" id="from_date" class="form-control w-auto me-3" placeholder="From Date">
                            
                            <label for="to_date" class="me-2 mb-0 text-light">To:</label>
                            <input type="date" id="to_date" class="form-control w-auto" placeholder="To Date">
                        </div>
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
                            <th scope="col">#</th>
                            <th scope="col">Invoice No</th>
                            <th scope="col">Date</th>
                            <th scope="col">Driver Name</th>
                            <th scope="col">Invoice Amount(Rs)</th>
                            <th scope="col">Actions</th>
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

        var table = $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("invoice-datatable") }}',
                data: function (d) {
                    d.role = role;    // Passing role
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'created_date', name: 'created_date'},
                {data: 'driver_name', name: 'driver_name'},
                {data: 'invoice_grand_total', name: 'invoice_grand_total'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#from_date, #to_date').on('change', function() {
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
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;
        }
    });
</script>

    
    <!-- [Page Specific JS] end -->
@endsection