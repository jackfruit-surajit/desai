@extends('admin.layouts.main')

@section('title', 'Vehicle Management')
@section('breadcrumb-item', 'Vehicle Management')

@section('breadcrumb-item-active', 'Add')

@section('css')
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


                    <form class="row g-3" method="post" action="{{route('vehicle-store')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">
                            <label for="" class="form-label">Vehicle's Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Vehicle's Name" name="vehicle_name" value="{{ (old('vehicle_name')!='') ? old('vehicle_name') : ''}}" >
                            @if ($errors->has('vehicle_name'))
                                <span class="help-block"> {{ $errors->first('vehicle_name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Vehicle's No<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Vehicle's No" name="vehicle_no" value="{{ (old('vehicle_no')!='') ? old('vehicle_no') : ''}}" >
                            @if ($errors->has('vehicle_no'))
                                <span class="help-block"> {{ $errors->first('vehicle_no') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Fuel Type<span class="required">*</span></label>
                            <select class="form-control" name="fuel_type" >
                                <option value="">Select</option>
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Electric">Electric</option>
                            </select>
                            @if ($errors->has('fuel_type'))
                                <span class="help-block"> {{ $errors->first('fuel_type') }} </span>
                            @endif
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
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
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    
        function passwordGenerator(){
            var length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            $('#password').val(retVal);
        }
        

    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
