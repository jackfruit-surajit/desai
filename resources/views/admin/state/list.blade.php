@extends('admin.layouts.main')

@section('title', 'State Management')
@section('breadcrumb-item', 'State Management')
@section('breadcrumb-item-active', 'States')

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
                            <h4 class="mb-0">
                                <i class="fa fa-map-location-dot text-primary me-2"></i> State List
                            </h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('state-add') }}" class="btn btn-primary float-end">
                                <span><i class="fa fa-plus me-1" aria-hidden="true"></i> Add New State</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error_msg'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-1"></i> {{ session('error_msg') }}
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
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">State Name</th>
                                    <th scope="col">State Code</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col" style="width: 15%;">Actions</th>
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
    <script src="{{ URL::asset('public/backend/assets/js/jquery-confirm.js') }}" type="text/javascript"></script>

    <script>
    $(document).ready(function () {
        $('#staff-management').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("state-datatable") }}'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'state_name', name: 'state_name'},
                {data: 'state_code', name: 'state_code'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    function changeStatusStaff(element) {
        var url = $(element).data('href');
        var status = $(element).is(':checked') ? 1 : 0;

        $.ajax({
            url: url,
            type: 'GET',
            data: { status: status },
            success: function (response) {
                if (response.status == 200) {
                    $.alert({
                        title: 'Success!',
                        content: response.msg,
                        type: 'green'
                    });
                } else {
                    $.alert({
                        title: 'Error!',
                        content: 'Failed to update status.',
                        type: 'red'
                    });
                }
            },
            error: function () {
                $.alert({
                    title: 'Error!',
                    content: 'An error occurred while updating status.',
                    type: 'red'
                });
            }
        });
    }
    </script>
    <!-- [Page Specific JS] end -->
@endsection

