@extends('admin.layouts.main')
@section('title', 'Monthly Attendance')
@section('breadcrumb-item', 'Staff Management')
@section('breadcrumb-item-active', 'Monthly Attendance')
@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset('public/backend/assets/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .absent-text {
            color: #dc3545;
            font-weight: bold;
        }
        .present-text {
            color: #198754;
            font-weight: bold;
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
                        <h4 class="mb-0">Attendance - {{ $driver ? $driver->name : 'Driver' }} ({{ date('F Y', strtotime($current_year . '-' . $current_month . '-01')) }})</h4>
                        <div class="mt-2">
                            <span class="badge bg-success" style="font-size: 14px;">Total Present: {{ $present_count }}</span>
                            <span class="badge bg-danger ms-2" style="font-size: 14px;">Total Absent: {{ $absent_count }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6 text-end">
                        <form method="GET" action="{{ route('driver-monthly-attendance', ['id' => base64_encode($driver->id)]) }}" class="d-inline-flex">
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

                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Selfie</th>
                            <th>Punch In</th>
                            <th>Break Time</th>
                            <th>Punch Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $date => $record)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($date)) }}</td>
                            @if($record)
                                <td class="present-text">Present</td>
                                <td>
                                    @if($record->selfie)
                                        <img src="{{ URL::asset('public/uploads/driver/' . $record->selfie) }}" height="50" width="50" alt="selfie">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $record->punch_in_time ? date('H:i:s', strtotime($record->punch_in_time)) : 'N/A' }}</td>
                                <td>{{ $record->break_time ? date('H:i:s', strtotime($record->break_time)) : 'N/A' }}</td>
                                <td>{{ $record->punch_out_time ? date('H:i:s', strtotime($record->punch_out_time)) : 'N/A' }}</td>
                            @else
                                <td class="absent-text">Absent</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            @endif
                        </tr>
                        @endforeach
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
