@extends('admin.layouts.main')

@section('title', 'Area Management')
@section('breadcrumb-item', 'Area Management')

@section('breadcrumb-item-active', 'Add')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Add New Area</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('area-store')}}" enctype="multipart/form-data">
                        @csrf
   
                        <div class="col-md-6">
                            <label for="" class="form-label">Area's Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Area Name" name="area_name" value="{{ (old('area_name')!='') ? old('area_name') : ''}}" >
                            @if ($errors->has('area_name'))
                                <span class="help-block"> {{ $errors->first('area_name') }} </span>
                            @endif
                        </div>


                        <!--<div class="col-md-6">-->
                        <!--    <label for="inputStatus" class="form-label">Status<span class="required">*</span> : </label>-->
                        <!--    <br/>-->
                        <!--    <input type="radio" name="status" value="1" checked> Active-->
                        <!--    <input type="radio" name="status" value="0"> Inactive-->
                        <!--    @if ($errors->has('status'))-->
                        <!--        <br/>-->
                        <!--        <span class="help-block"> {{ $errors->first('status') }} </span>-->
                        <!--    @endif-->
                        <!--</div>-->
                        
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
                            <a href="{{route('areas')}}" class="btn btn-outline-secondary">Cancel</a>
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

        
    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
