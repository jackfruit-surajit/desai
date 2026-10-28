@extends('admin.layouts.main')

@section('title', 'Customer Management')
@section('breadcrumb-item', 'Customer Management')

@section('breadcrumb-item-active', 'Edit')

@section('css')
<style>
    .required {
        color: red;
    }
    .uppercase-input {
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <h5>Edit Customer/Shop</h5>
                </div>
                <div class="card-body">

                    @if (session('error_msg'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error_msg') }}
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

                    <form class="row g-3" method="post" action="{{route('customer-update')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{$model->id}}">
                        
                        <h5>Shop Details</h5>
                        
                        <div class="col-md-6">
                            <label for="shop_name" class="form-label">Shop's Name<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="shop_name" placeholder="Shop's Name" name="shop_name" value="{{ old('shop_name', $model->shop_name) }}" required>
                            @if ($errors->has('shop_name'))
                                <span class="help-block text-danger"> {{ $errors->first('shop_name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="shop_photo" class="form-label">Shop Photo</label>
                            <input type="file" class="form-control" id="shop_photo" name="shop_photo">
                            @if ($errors->has('shop_photo'))
                                <span class="help-block text-danger"> {{ $errors->first('shop_photo') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="area_id" class="form-label"> Area <span class="required">*</span></label>
                            <select class="form-control select2" name="area_id" id="area_id">
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                <option value="{{$area->id}}" @if(old('area_id', $model->area_id) == $area->id) selected @endif>{{$area->area_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('area_id'))
                                <span class="help-block text-danger"> {{ $errors->first('area_id') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="road_id" class="form-label"> Road / Route <span class="required">*</span></label>
                            <select class="form-control select2" name="road_id" id="road_id">
                                <option value="">Select Road / Route</option>
                                @foreach($roads as $road)
                                <option value="{{$road->id}}" @if(old('road_id', $model->road_id) == $road->id) selected @endif>{{$road->road_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('road_id'))
                                <span class="help-block text-danger"> {{ $errors->first('road_id') }} </span>
                            @endif
                        </div>
                        
                        @if($model->shop_photo)
                        <div class="col-md-12">
                            <label class="form-label">Current Photo:</label><br>
                            <img src="{{ URL::asset(asset_path('uploads/customer/' . $model->shop_photo)) }}" height="120" width="160" class="img-thumbnail">
                        </div>
                        @endif
                        
                        <hr class="text-white">

                        <h5 class="text-white">Personal Details</h5>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Customer's Name<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="name" placeholder="Customer's Name" name="name" value="{{ old('name', $model->name) }}" required>
                            @if ($errors->has('name'))
                                <span class="help-block text-danger"> {{ $errors->first('name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email', $model->email) }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block text-danger"> {{ $errors->first('email') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Contact No<span class="required">*</span></label>
                            <input type="text" class="form-control" id="phone" placeholder="Contact No" name="phone" value="{{ old('phone', $model->mobile_no) }}" required>
                            @if ($errors->has('phone'))
                                <span class="help-block text-danger"> {{ $errors->first('phone') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="gst_no" class="form-label">GST NO</label>
                            <input type="text" class="form-control uppercase-input" id="gst_no" placeholder="GST No" name="gst_no" value="{{ old('gst_no', $model->gst_no) }}">
                            @if ($errors->has('gst_no'))
                                <span class="help-block text-danger"> {{ $errors->first('gst_no') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="pan_no" class="form-label">PAN NO</label>
                            <input type="text" class="form-control uppercase-input" id="pan_no" placeholder="PAN No" name="pan_no" value="{{ old('pan_no', $model->pan_no) }}">
                            @if ($errors->has('pan_no'))
                                <span class="help-block text-danger"> {{ $errors->first('pan_no') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" @if(old('status', $model->status) == '1') checked @endif>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" @if(old('status', $model->status) == '0') checked @endif>
                                <label class="form-check-label text-white" for="statusInactive">
                                    Inactive
                                </label>
                            </div>
                        
                            @if ($errors->has('status'))
                                <br>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        
                        <hr class="text-white">
                        <h5 class="text-white">GPS location Details</h5>
 
                        <div class="col-md-6">
                            <label for="state" class="form-label">State<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="state" placeholder="State" name="state" value="{{ old('state', $model->state) }}" required>
                            @if ($errors->has('state'))
                                <span class="help-block text-danger"> {{ $errors->first('state') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="city" class="form-label">City<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="city" placeholder="City" name="city" value="{{ old('city', $model->city) }}" required>
                            @if ($errors->has('city'))
                                <span class="help-block text-danger"> {{ $errors->first('city') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="pin_code" class="form-label">PIN Code<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="pin_code" placeholder="PIN Code" name="pin_code" value="{{ old('pin_code', $model->pin_code) }}" required>
                            @if ($errors->has('pin_code'))
                                <span class="help-block text-danger"> {{ $errors->first('pin_code') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="landmark" class="form-label">Land Mark<span class="required">*</span></label>
                            <input type="text" class="form-control uppercase-input" id="landmark" placeholder="Land Mark" name="landmark" value="{{ old('landmark', $model->landmark) }}" required>
                            @if ($errors->has('landmark'))
                                <span class="help-block text-danger"> {{ $errors->first('landmark') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">Latitude<span class="required">*</span></label>
                            <input type="text" class="form-control" id="latitude" placeholder="Latitude" name="latitude" value="{{ old('latitude', $model->latitude) }}" required>
                            @if ($errors->has('latitude'))
                                <span class="help-block text-danger"> {{ $errors->first('latitude') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">Longitude<span class="required">*</span></label>
                            <input type="text" class="form-control" id="longitude" placeholder="Longitude" name="longitude" value="{{ old('longitude', $model->longitude) }}" required>
                            @if ($errors->has('longitude'))
                                <span class="help-block text-danger"> {{ $errors->first('longitude') }} </span>
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
            $('.select2').select2();

            $('.uppercase-input').on('input', function() {
                this.value = this.value.toUpperCase();
            });

            $('form').on('submit', function() {
                $('.uppercase-input').each(function() {
                    this.value = this.value.toUpperCase();
                });
            });
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
