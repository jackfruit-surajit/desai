@extends('admin.layouts.main')
@section('title', 'Driver Attendance')
@section('breadcrumb-item', 'Staff Management')
@section('breadcrumb-item-active', 'Driver Attendance')
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
                        <h4 class="mb-0">Driver Attendance</h4>
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table class="table table-striped table-bordered nowrap" id="staff-management">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Driver Name</th>
                            <th scope="col">Selfie</th>
                            <th scope="col">Punch In Time</th>
                            <th scope="col">Break Time</th>
                            <th scope="col">Punch Out Time</th>
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
        $('#staff-management').DataTable({
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("driver-attendance-datatable") }}',
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'driver_name', name: 'driver_name'},
                {data: 'selfie', name: 'selfie', orderable: false, searchable: false},
                {data: 'punch_in_time', name: 'punch_in_time'},
                {data: 'break_time', name: 'break_time'},
                {data: 'punch_out_time', name: 'punch_out_time'}
            ]
        });
    });
    </script>
    <!-- [Page Specific JS] end -->
@endsection

