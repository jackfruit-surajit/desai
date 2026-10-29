@extends('warehouse.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')

@section('breadcrumb-item-active', 'View')

@section('css')
<style>
    /* High contrast text for form controls and labels */
    .form-label {
        color: #0f172a !important;
        font-weight: 600 !important;
    }
    .form-control {
        color: #0f172a !important;
        border: 1px solid #cbd5e1;
    }
    /* Disabled and readonly inputs styling for maximum legibility */
    .form-control:disabled, 
    .form-control[readonly], 
    select.form-control:disabled {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        opacity: 1 !important;
        font-weight: 600 !important;
        border: 1px solid #94a3b8 !important;
        -webkit-text-fill-color: #0f172a !important;
    }
    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #f1f5f9 !important;
        border: 1px solid #94a3b8 !important;
        opacity: 1 !important;
    }
    .select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-weight: 600 !important;
        -webkit-text-fill-color: #0f172a !important;
    }
</style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        
        @php
            if ($model->status == '0') {
                $status = '<span class="badge bg-warning text-dark fw-bold float-end px-3 py-2" style="font-size:13px;">Pending</span>';
            } else if ($model->status == '1') {
                $status = '<span class="badge bg-success fw-bold float-end px-3 py-2" style="font-size:13px;">Verified</span>';
            } else if ($model->status == '2') {
                $status = '<span class="badge bg-danger fw-bold float-end px-3 py-2" style="font-size:13px;">Rejected</span>';
            }
        @endphp

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0 text-dark fw-bold">View Assigning Product Details</h4>
                        </div>
                        <div class="col-md-6">
                            @php echo $status; @endphp
                        </div>
                    </div>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('warehouse-product-update')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="warehouse_products_id" value="{{$model->id}}">
                        
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <select name="product_id" class="form-control product-select" disabled>
                                <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" @if($model->product_id == $product->id) {{'selected'}} @endif>{{ $product->name }}</option>
                                    @endforeach
                            </select>
                            
                            @error('product_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quantity (Case)</label>
                            <input type="number" name="per_case_quantity" class="form-control" placeholder="Quantity in Case" min="1" value="{{$model->per_case_quantity}}" disabled>
                            @error('per_case_quantity')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="required text-danger">*</span></label>
                            <select name="status" class="form-control product-select" required>
                                <option value="1" @if($model->status == '1') {{'selected'}} @endif>Confirmed</option>
                                <option value="2" @if($model->status == '2') {{'selected'}} @endif>Reject</option>
                                <option value="0" @if($model->status == '0') {{'selected'}} @endif>Pending</option>
                            </select>
                            
                            @error('status')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="4">{{$model->comment}}</textarea>
                            @error('comment')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($model->status == '0')
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('warehouse-products')}}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @else
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('warehouse-products')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
                        @endif
                        
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
