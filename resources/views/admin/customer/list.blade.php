@extends('admin.layouts.main')

@section('title', 'Customer Management')
@section('breadcrumb-item', 'Customer Management')

@section('breadcrumb-item-active', 'Customers')
@php
    $user_role = Auth::guard('backend')->user()->role_id;
@endphp

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
    <!-- [ Main Content ] start -->
    
    
    <div class="row">
          
          <!-- Multiple Table Control Elements start -->
        <div class="col-sm-12">

            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">

                    <div class="col-lg-6">
                        <h4 class="mb-0">Customers / Shops</h4>
                    </div>

                    <div class="col-lg-6">
                        <a href="{{route('customer-add')}}" >
                            <button class="btn btn-secondary float-end me-2" tabindex="0" aria-controls="custom-btn" type="button">
                                <span>Add New <i class="fa fa-plus" aria-hidden="true"></i></span>
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
                            <th scope="col">#</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Owner Name</th>
                            <th scope="col">Shop Name</th>
                            {{-- <th scope="col">Mobile No</th> --}}
                            <th scope="col">Route</th>
                            <th scope="col">Area</th>
                            <!--<th scope="col">Address</th>-->
                            <th scope="col">Status</th>
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

        $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("customer-datatable") }}',
                data: function (d) {
                    d.role = role;    // Passing role
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'image', name: 'image'},
                {data: 'name', name: 'name'},
                {data: 'shop_name', name: 'shop_name'},
                //{data: 'mobile_no', name: 'mobile_no'},
                {data: 'road_name', name: 'road_name'},
                {data: 'area_name', name: 'area_name'},
                {data: 'status', name: 'status'},
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