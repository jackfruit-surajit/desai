@extends('warehouse.layouts.main')

@section('title', 'Driver Management')
@section('breadcrumb-item', 'Driver Management')
@section('breadcrumb-item-active', 'Products')

@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
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

                    <div class="col-lg-8">
                        <h4 class="mb-0 text-white fw-bold"><i class="fa fa-boxes-stacked me-2"></i>Product List of {{$driver->name}} ( {{date('d F, Y',strtotime($driver->created_at))}} )</h4>
                    </div>

                    <div class="col-lg-4 text-end">
                        <a href="{{route('driver-management-report')}}" class="btn btn-outline-light btn-sm"><i class="fa fa-arrow-left me-1"></i> Back to Report</a>
                    </div>

                </div>
              </div>
              <div class="card-body">

                        @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error_msg'))
                                <div class="alert alert-danger">
                                    {{ session('error_msg') }}
                                </div>
                            @endif
                            @if (isset($errors) && $errors->any())
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
                            <th scope="col" style="width: 80px;">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col" style="width: 240px;">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key=>$product)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$product->product_name}}</td>
                            <td>
                                @php
                                    $cases = (int)$product->quantity;
                                    $bottles = (int)($product->bottles ?? 0);
                                @endphp
                                @if ($cases > 0 && $bottles > 0)
                                    <span class="badge bg-info text-dark fw-bold" style="font-size:13px;">{{ $cases }} Cases + {{ $bottles }} Bottles</span>
                                @elseif ($cases > 0)
                                    <span class="badge bg-primary text-white fw-bold" style="font-size:13px;">{{ $cases }} Cases</span>
                                @elseif ($bottles > 0)
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size:13px;">{{ $bottles }} Bottles</span>
                                @else
                                    <span class="badge bg-secondary text-white fw-bold" style="font-size:13px;">0 Cases</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No products assigned.</td>
                        </tr>
                        @endforelse
                    </tbody>
                   </table>
                </div>
                
                <div class="col-md-12 text-end btn-page mt-3">
                    <a href="{{route('driver-management-report')}}" class="btn btn-outline-secondary">Back</a>
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
    <script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#staff-management')) {
                $('#staff-management').DataTable({
                    responsive: true,
                    pageLength: 25
                });
            }
        });
    </script>
    <!-- [Page Specific JS] end -->
@endsection
