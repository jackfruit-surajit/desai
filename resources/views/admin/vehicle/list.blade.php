@extends('admin.layouts.main')

@section('title', 'Vehicle Management')
@section('breadcrumb-item', 'Vehicle Management')
@section('breadcrumb-item-active', 'Vehicles')

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

                    <div class="col-lg-6">
                        <h4 class="mb-0">Vehicles</h4>
                    </div>
                    <div class="col-lg-6">
                      
                        <a href="{{route('vehicle-add')}}" >
                            <button class="btn btn-secondary float-end me-2" tabindex="0" aria-controls="custom-btn" type="button">
                                <span>Add New <i class="fa fa-plus" aria-hidden="true"></i></span>
                            </button>
                        </a>
                            
                    </div>
                </div>
              </div>
              <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('success_msg'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_msg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error_msg'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error_msg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                <div class="table-responsive dt-responsive">
                  <table class="table table-striped table-bordered nowrap" id="staff-management">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Vehicle Name</th>
                            <th scope="col">Vehicle No</th>
                            <th scope="col">Chassi No</th>
                            <th scope="col">Engine No</th>
                            <th scope="col">GPS/IMEI No</th>
                            <th scope="col">Pollution</th>
                            <th scope="col">Fitness</th>
                            <th scope="col">PUC</th>
                            <th scope="col">Fuel Type</th>
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
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <script src="{{ URL::asset('public/backend/assets/js/jquery-confirm.js') }}" type="text/javascript"></script>
    
    <script>
    $(document).ready(function () {
        var role = getUrlParameter('role');

        $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("vehicle-datatable") }}',
                data: function (d) {
                    d.role = role;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'vehicle_name', name: 'vehicle_name'},
                {data: 'vehicle_no', name: 'vehicle_no'},
                {data: 'chassis_no', name: 'chassis_no', defaultContent: '-'},
                {data: 'engine_no', name: 'engine_no', defaultContent: '-'},
                {data: 'gps_imei_no', name: 'gps_imei_no', defaultContent: '-'},
                {data: 'pollution', name: 'pollution', defaultContent: '-'},
                {data: 'fitness', name: 'fitness', defaultContent: '-'},
                {data: 'puc', name: 'puc', defaultContent: '-'},
                {data: 'fuel_type', name: 'fuel_type', defaultContent: '-'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

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
