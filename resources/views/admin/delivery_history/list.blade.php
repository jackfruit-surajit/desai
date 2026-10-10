@extends('admin.layouts.main')

@section('title', 'Shop Delivery History')
@section('breadcrumb-item', 'Delivery History')

@section('breadcrumb-item-active', 'List')

@section('css')

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

@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5>Shop Delivery History</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="staff-management" class="table table-striped table-hover table-bordered table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Shop Name</th>
                            <th scope="col">Driver Name</th>
                            <th scope="col">Delivery Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Selfie</th>
                            <th scope="col">Note</th>
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
    <script>
        $(document).ready(function () {
            var table = $('#staff-management').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('shop-delivery-history.datatable') }}",
                    type: "GET",
                    data: function(d) {
                        d.filter_date = $('#filter_date').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'shop_name', name: 't2.shop_name'},
                    {data: 'driver_name', name: 't3.name'},
                    {data: 'delivery_date', name: 't1.delivery_date'},
                    {data: 'delivery_status', name: 't1.delivery_status'},
                    {data: 'selfie', name: 'selfie', orderable: false, searchable: false},
                    {
                        data: 'delivery_note', 
                        name: 't1.delivery_note',
                        render: function(data, type, row) {
                            return '<div style="white-space: pre-wrap; word-wrap: break-word; min-width: 150px; max-width: 300px;">' + (data ? data : '') + '</div>';
                        }
                    }
                ]
            });
            
            $('#filter_date').on('change', function() {
                table.draw();
            });
        });
    </script>
    <!-- [Page Specific JS] end -->
@endsection

