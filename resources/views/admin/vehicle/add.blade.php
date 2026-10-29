@extends('admin.layouts.main')

@section('title', 'Vehicle Management')
@section('breadcrumb-item', 'Vehicle Management')

@section('breadcrumb-item-active', 'Add')

@section('css')
<style>
    .required {
        color: red;
    }
</style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <h5>Add New Vehicle</h5>
                </div>
                <div class="card-body">

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

                    <form class="row g-3" method="post" action="{{route('vehicle-store')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">
                            <label for="vehicle_name" class="form-label">Vehicle's Name <span class="required">*</span></label>
                            <input type="text" id="vehicle_name" class="form-control" placeholder="Vehicle's Name" name="vehicle_name" value="{{ old('vehicle_name') }}" required>
                            @if ($errors->has('vehicle_name'))
                                <span class="text-danger"> {{ $errors->first('vehicle_name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="vehicle_no" class="form-label">Vehicle's No <span class="required">*</span></label>
                            <input type="text" id="vehicle_no" class="form-control" placeholder="Vehicle's No" name="vehicle_no" value="{{ old('vehicle_no') }}" required>
                            @if ($errors->has('vehicle_no'))
                                <span class="text-danger"> {{ $errors->first('vehicle_no') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="fuel_type" class="form-label">Fuel Type <span class="required">*</span></label>
                            <select class="form-control" id="fuel_type" name="fuel_type">
                                <option value="">Select Fuel Type</option>
                                <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                                <option value="CNG" {{ old('fuel_type') == 'CNG' ? 'selected' : '' }}>CNG</option>
                                <option value="Petrol" {{ old('fuel_type') == 'Petrol / CNG' ? 'selected' : '' }}>Petrol / CNG</option>
                            </select>
                            @if ($errors->has('fuel_type'))
                                <span class="text-danger"> {{ $errors->first('fuel_type') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="chassis_no" class="form-label">Chassi No.</label>
                            <input type="text" id="chassis_no" class="form-control" placeholder="Chassi No." name="chassis_no" value="{{ old('chassis_no') }}">
                            @if ($errors->has('chassis_no'))
                                <span class="text-danger"> {{ $errors->first('chassis_no') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="engine_no" class="form-label">Engine No.</label>
                            <input type="text" id="engine_no" class="form-control" placeholder="Engine No." name="engine_no" value="{{ old('engine_no') }}">
                            @if ($errors->has('engine_no'))
                                <span class="text-danger"> {{ $errors->first('engine_no') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="gps_imei_no" class="form-label">GPS/IMEI No.</label>
                            <input type="text" id="gps_imei_no" class="form-control" placeholder="GPS/IMEI No." name="gps_imei_no" value="{{ old('gps_imei_no') }}">
                            @if ($errors->has('gps_imei_no'))
                                <span class="text-danger"> {{ $errors->first('gps_imei_no') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="pollution" class="form-label">Pollution</label>
                            <input type="text" id="pollution" class="form-control" placeholder="Pollution" name="pollution" value="{{ old('pollution') }}">
                            @if ($errors->has('pollution'))
                                <span class="text-danger"> {{ $errors->first('pollution') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="fitness" class="form-label">Fitness</label>
                            <input type="text" id="fitness" class="form-control" placeholder="Fitness" name="fitness" value="{{ old('fitness') }}">
                            @if ($errors->has('fitness'))
                                <span class="text-danger"> {{ $errors->first('fitness') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="puc" class="form-label">PUC</label>
                            <input type="text" id="puc" class="form-control" placeholder="PUC" name="puc" value="{{ old('puc') }}">
                            @if ($errors->has('puc'))
                                <span class="text-danger"> {{ $errors->first('puc') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ old('status') === '0' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="statusInactive">
                                    Inactive
                                </label>
                            </div>
                        
                            @if ($errors->has('status'))
                                <br>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>

                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('vehicles')}}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
          
        </div>
        
    </div>
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
    <!-- [Page Specific JS] end -->
@endsection
