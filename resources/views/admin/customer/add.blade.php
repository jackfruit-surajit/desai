@extends('admin.layouts.main')

@section('title', 'Customer Management')
@section('breadcrumb-item', 'Customer Management')

@section('breadcrumb-item-active', 'Add')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Add New Customer</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('customer-store')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <h5>Personal Details</h5>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Customer's Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Customer's Name" name="name" value="{{ (old('name')!='') ? old('name') : ''}}" >
                            @if ($errors->has('name'))
                                <span class="help-block"> {{ $errors->first('name') }} </span>
                            @endif
                        </div>
                        
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ (old('email')!='') ? old('email') : ''}}" >
                            @if ($errors->has('email'))
                                <span class="help-block"> {{ $errors->first('email') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Contact No<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Contact No" name="phone" value="{{ (old('phone')!='') ? old('phone') : ''}}" >
                            @if ($errors->has('phone'))
                                <span class="help-block"> {{ $errors->first('phone') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">GST NO</label>
                            <input type="text" class="form-control" placeholder="GST No" name="gst_no" value="{{ (old('gst_no')!='') ? old('gst_no') : ''}}" >
                            @if ($errors->has('gst_no'))
                                <span class="help-block"> {{ $errors->first('gst_no') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">PAN NO</label>
                            <input type="text" class="form-control" placeholder="PAN No" name="pan_no" value="{{ (old('pan_no')!='') ? old('pan_no') : ''}}" >
                            @if ($errors->has('pan_no'))
                                <span class="help-block"> {{ $errors->first('pan_no') }} </span>
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
                        
                        
                        <hr>
                        <h5>Shop Details</h5>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Shop's Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Shop's Name" name="shop_name" value="{{ (old('shop_name')!='') ? old('shop_name') : ''}}" >
                            @if ($errors->has('shop_name'))
                                <span class="help-block"> {{ $errors->first('shop_name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputImage" class="form-label">Shop Photo <span class="required">*</span></label>
                            <input type="file" class="form-control" name="shop_photo" >
                            @if ($errors->has('shop_photo'))
                                <span class="help-block"> {{ $errors->first('shop_photo') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label"> Area <span class="required">*</span></label>
                            <select class="form-control" name="area_id" >
                                <option value="">Select</option>
                                
                                @foreach($areas as $area)
                                <option value="{{$area->id}}">{{$area->area_name}}</option>
                                @endforeach
                                
                            </select>
                            @if ($errors->has('area_id'))
                                <span class="help-block"> {{ $errors->first('area_id') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label"> Road / Route <span class="required">*</span></label>
                            <select class="form-control" name="road_id" >
                                <option value="">Select</option>
                                
                                @foreach($roads as $road)
                                <option value="{{$road->id}}">{{$road->road_name}}</option>
                                @endforeach
                                
                            </select>
                            @if ($errors->has('road_id'))
                                <span class="help-block"> {{ $errors->first('road_id') }} </span>
                            @endif
                        </div>
                        
                        
                        <hr>
                        <h5>GPS location Details</h5>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">State<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="State" name="state" value="{{ (old('state')!='') ? old('state') : ''}}">
                            @if ($errors->has('state'))
                                <span class="help-block"> {{ $errors->first('state') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">City<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="City" name="city" value="{{ (old('city')!='') ? old('city') : ''}}">
                            @if ($errors->has('state'))
                                <span class="help-block"> {{ $errors->first('city') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">PIN Code<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="PIN Code" name="pin_code" value="{{ (old('pin_code')!='') ? old('pin_code') : ''}}">
                            @if ($errors->has('pin_code'))
                                <span class="help-block"> {{ $errors->first('pin_code') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Land Mark<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Land Mark" name="landmark" value="{{ (old('landmark')!='') ? old('landmark') : ''}}">
                            @if ($errors->has('landmark'))
                                <span class="help-block"> {{ $errors->first('landmark') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Latitude<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Latitude" name="latitude" value="{{ (old('latitude')!='') ? old('latitude') : ''}}" >
                            @if ($errors->has('latitude'))
                                <span class="help-block"> {{ $errors->first('latitude') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Longitude<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Longitude" name="longitude" value="{{ (old('longitude')!='') ? old('longitude') : ''}}" >
                            @if ($errors->has('longitude'))
                                <span class="help-block"> {{ $errors->first('longitude') }} </span>
                            @endif
                        </div>

                        
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('customer-list')}}" class="btn btn-outline-secondary">Cancel</a>
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
