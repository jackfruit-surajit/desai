@extends('admin.layouts.main')
@section('title', 'Sales Executive Area Logs')
@section('breadcrumb-item', 'Staff Management')
@section('breadcrumb-item-active', 'Sales Executive Area Logs')
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
                        <h4 class="mb-0">Sales Executive Area Logs</h4>
                    </div>
                    <div class="col-lg-6">
                        <input type="date" id="filter_date" class="form-control" style="width: 200px; float: right;" placeholder="Filter by date">
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table class="table table-striped table-bordered nowrap" id="staff-management">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Executive Name</th>
                            <th scope="col">Area Name</th>
                            <th scope="col">Log Time</th>
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
        var table = $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("sales-executive-area-logs-list-datatable") }}',
                data: function (d) {
                    d.filter_date = $('#filter_date').val();
                    d.executive_id = getUrlParameter('executive_id');
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'executive_name', name: 'executive_name'},
                {data: 'area_name', name: 'area_name'},
                {data: 'created_at', name: 'created_at'}
            ]
        });

        $('#filter_date').on('change', function() {
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

