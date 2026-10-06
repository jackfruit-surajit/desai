@extends('admin.layouts.main')

@section('title', 'Staff Management')
@section('breadcrumb-item', 'Staff Management')

@section('breadcrumb-item-active', 'Add')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Add New Staff</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('user-store')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">
                            <label for="" class="form-label">Roles<span class="required">*</span></label>
                            <select class="form-control select2" name="role" id= "role" onchange="selectDivision()">
                                <option class="option selected focus" selected disabled>Select Role</option>
                                @forelse($roles as $role)
                                    <option value="{{ $role->id }}" {{ (old('role')!='') ? ($role->id==old('role'))?'selected':'' : ''}}>{{$role->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('role'))
                                <span class="help-block"> {{ $errors->first('role') }} </span>
                            @endif
                        </div>



                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Staff Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Staff Name" name="name" value="{{ (old('name')!='') ? old('name') : ''}}" >
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
                        
                        <div class="col-md-6 d-none" id="vehicle_details">
                            <label for="" class="form-label">Transport Vehicle<span class="required">*</span></label>
                            <select class="form-control select2" name="vehicle_id" id= "vehicle_id">
                                <option class="option selected focus" selected disabled>Select</option>
                                @forelse($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{$vehicle->vehicle_name}} - {{$vehicle->vehicle_no}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('vehicle_id'))
                                <span class="help-block"> {{ $errors->first('vehicle_id') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputImage" class="form-label">Staff Photo</label>
                            <input type="file" class="form-control" name="image" >
                            @if ($errors->has('image'))
                                <span class="help-block"> {{ $errors->first('image') }} </span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Password<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Password" name="password" id="password" value="{{ (old('password')!='') ? old('password') : ''}}" >
                            @if ($errors->has('password'))
                                <span class="help-block"> {{ $errors->first('password') }} </span>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <label for="" class="form-label"></label>
                            <br/>
                            <a href="javascript:;" onclick="passwordGenerator();" class="btn btn-success" > Generate</a>
                        </div>
                        <div class="col-md-1">
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
    
        function toggleVehicleDetails() {
            if ($("#role").val() == '3') {
                $("#vehicle_details").removeClass("d-none");
            } else {
                $("#vehicle_details").addClass("d-none");
            }
        }
    
        $("#role").change(toggleVehicleDetails);
    
        toggleVehicleDetails();



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
