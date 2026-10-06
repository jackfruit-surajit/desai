@extends('admin.layouts.main')

@section('title', 'Customer Management')
@section('breadcrumb-item', 'Customer Profile')

@section('breadcrumb-item-active', 'Assign Roads')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Customer's Assigned Road List</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        
                        <h5>Personal Information</h5>
                        
                        
                        
                        <div class="col-md-4">
                            <label for="" class="form-label">Student Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Student Name" name="name" value="{{ (old('name')!='') ? old('name') : ''}}" >
                            @if ($errors->has('name'))
                                <span class="help-block"> {{ $errors->first('name') }} </span>
                            @endif
                        </div>
                        
                       



                        
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('customer-list')}}" class="btn btn-outline-secondary">Back</a>
                            <!--<button type="submit" class="btn btn-primary">Submit</button>-->
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
