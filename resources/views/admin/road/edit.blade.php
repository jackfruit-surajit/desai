@extends('admin.layouts.main')

@section('title', 'Route Management')
@section('breadcrumb-item', 'Route Management')

@section('breadcrumb-item-active', 'Edit')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Edit Route</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('road-update')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="col-md-6">
                            <label for="" class="form-label">Area<span class="required">*</span></label>
                            <select class="form-control" name="area_id" >
                                <option value="">Select Area</option>
                                
                                @foreach($areas as $area)
                                <option value="{{$area->id}}" @if($area->id == $model->area_id){{'selected'}} @endif>{{$area->area_name}}</option>
                                @endforeach
                                
                            </select>
                            @if ($errors->has('area_id'))
                                <span class="help-block"> {{ $errors->first('area_id') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Route <span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Road's Name" name="road_name" value="{{ (old('road_name')!='') ? old('road_name') : $model->road_name}}" >
                            @if ($errors->has('road_name'))
                                <span class="help-block"> {{ $errors->first('road_name') }} </span>
                            @endif
                        </div>

                        <!--<div class="col-md-12">-->
                        <!--    <label for="" class="form-label">Full Address <span class="required">*</span></label>-->
                        <!--    <textarea class="form-control" name="full_address" >{{ (old('full_address')!='') ? old('full_address') : $model->full_address}}</textarea>-->
                        <!--    @if ($errors->has('full_address'))-->
                        <!--        <span class="help-block"> {{ $errors->first('full_address') }} </span>-->
                        <!--    @endif-->
                        <!--</div>-->

                        
                        <div class="col-md-6">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" @if($model->status == '1'){{'checked'}} @endif>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" @if($model->status == '0'){{'checked'}} @endif>
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
                            <a href="{{route('roads')}}" class="btn btn-outline-secondary">Cancel</a>
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
