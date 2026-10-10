@extends('admin.layouts.main')

@section('title', 'Staff Management')
@section('breadcrumb-item', 'Staff Management')

@section('breadcrumb-item-active', 'Assigned Warehouse')

@section('css')

@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Assign Warehouse to {{$model->name}}</h5>
                </div>
                <div class="card-body">

                    <form class="row g-3" method="post" action="{{route('driver-warehouse-update')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="driver_id" id="driver_id" value="{{$model->id}}">
                        <div class="col-md-12">
                            <label for="" class="form-label">Warehouse<span class="required">*</span></label>
                            <select class="form-control" name="warehouse" id="warehouse">
                                <option value="">Select</option>
                                @foreach($warehouses as $warehouse)
                                 <option value="{{$warehouse->id}}" @if($select_warehouse && $select_warehouse->warehouse_id == $warehouse->id) {{'selected'}} @endif>{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('warehouse'))
                                <span class="help-block"> {{ $errors->first('warehouse') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('drivers')}}" class="btn btn-outline-secondary">Back</a>
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
    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
