@extends('admin.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')

@section('breadcrumb-item-active', 'Edit')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Edit Product</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('product-update')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="product_id" value="{{$model->id}}">
   
                        <div class="col-md-6">
                            <label for="" class="form-label">Product's Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Product Name" name="product_name" value="{{ (old('product_name')!='') ? old('product_name') : $model->name}}" >
                            @if ($errors->has('product_name'))
                                <span class="help-block"> {{ $errors->first('product_name') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">HSN/SAC</label>
                            <input type="text" class="form-control" placeholder="HSN/SAC Code" name="hsn_code" value="{{ (old('hsn_code')!='') ? old('hsn_code') : $model->hsn_code}}" >
                            @if ($errors->has('hsn_code'))
                                <span class="help-block"> {{ $errors->first('hsn_code') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Per Case Quantity<span class="required">*</span> ( 1 case = ? Bottle)</label>
                            <input type="text" class="form-control" placeholder="Per Case Quantity" id="per_case_quantity" name="per_case_quantity" value="{{ (old('per_case_quantity')!='') ? old('per_case_quantity') : $model->per_case_quantity}}" >
                            @if ($errors->has('per_case_quantity'))
                                <span class="help-block"> {{ $errors->first('per_case_quantity') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">Per Case Price<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Per Case Price" name="per_case_price" id="per_case_price" value="{{ (old('per_case_price')!='') ? old('per_case_price') : $model->per_case_price}}" >
                            @if ($errors->has('per_case_price'))
                                <span class="help-block"> {{ $errors->first('per_case_price') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">Per Bottle Price<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Per Bottle Price" name="per_bottle_price" id="per_bottle_price" value="{{ (old('per_bottle_price')!='') ? old('per_bottle_price') : $model->per_bottle_price}}" >
                            @if ($errors->has('per_bottle_price'))
                                <span class="help-block"> {{ $errors->first('per_bottle_price') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">CGST(%)<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="CGST" id="cgst" name="cgst" value="{{ (old('cgst')!='') ? old('cgst') : $model->cgst}}" >
                            @if ($errors->has('cgst'))
                                <span class="help-block"> {{ $errors->first('cgst') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="" class="form-label">SGST(%)<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="SGST" id="sgst" name="sgst" value="{{ (old('sgst')!='') ? old('sgst') : $model->sgst}}" >
                            @if ($errors->has('sgst'))
                                <span class="help-block"> {{ $errors->first('sgst') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" @if($model->status == '1') {{'checked'}} @endif>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" @if($model->status == '0') {{'checked'}} @endif>
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
                            <a href="{{route('products')}}" class="btn btn-outline-secondary">Cancel</a>
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

        $('#per_case_price').keyup(function(){
            var case_price = $(this).val();
            var per_case_quantity = $("#per_case_quantity").val();
            var bottle_price =  case_price / per_case_quantity;
            
            $('#per_bottle_price').val(bottle_price.toFixed(2));
        });
    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
