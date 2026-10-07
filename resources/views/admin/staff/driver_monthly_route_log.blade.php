@extends('admin.layouts.main')
@section('title', 'Monthly Route Log')
@section('breadcrumb-item', 'Staff Management')
@section('breadcrumb-item-active', 'Monthly Route Log')
@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset('public/backend/assets/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .jconfirm-content {
            overflow: hidden !important;
        }
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h4 class="mb-0">Route Log - {{ $driver ? $driver->name : 'Driver' }} ({{ date('F Y', strtotime($current_year . '-' . $current_month . '-01')) }})</h4>
                    </div>
                    <div class="col-lg-6 text-end">
                        <form method="GET" action="{{ route('driver-monthly-route-log', ['id' => base64_encode($driver->id)]) }}" class="d-inline-flex">
                            <select name="month" class="form-select me-2" style="width: auto;">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $current_month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                    </option>
                                @endfor
                            </select>
                            <select name="year" class="form-select me-2" style="width: auto;">
                                @for($y=date('Y')-2; $y<=date('Y'); $y++)
                                    <option value="{{ $y }}" {{ $current_year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                        <a href="{{ route('drivers') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Route Name</th>
                            <th>Log Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $log->route_name ?? 'N/A' }}</td>
                            <td>{{ $log->created_at ? date('d-m-Y H:i:s', strtotime($log->created_at)) : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No route logs found for this month.</td>
                        </tr>
                        @endforelse
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
    <!-- [Page Specific JS] end -->
@endsection

