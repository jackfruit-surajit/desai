@extends('manufacture.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')

@section('breadcrumb-item-active', 'Edit')

@section('css')
<link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        
        @php
            if ($model->status == '0') {
                $status = '<span class="badge bg-warning float-end">Pending</span>';
            } else if ($model->status == '1') {
                $status = '<span class="badge bg-success float-end">Verified</span>';
            } else if ($model->status == '2') {
                $status = '<span class="badge bg-danger float-end">Rejected</span>';
            }
        @endphp

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">Edit Assigning Product Details</h4>
                        </div>
                        <div class="col-md-6">
                            @php echo $status; @endphp
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error_msg'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-triangle-exclamation me-1"></i> Error:</strong> {{ session('error_msg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fa fa-triangle-exclamation me-1"></i> Please check errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="row g-3" method="post" action="{{route('manufacture-product-update')}}" id="editAssignForm" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="warehouse_products_id" value="{{$model->id}}">
                        <input type="hidden" id="original_quantity" value="{{$model->per_case_quantity}}">
                        <input type="hidden" id="original_product_id" value="{{$model->product_id}}">
   
                        <div class="col-md-12">
                            <label class="form-label">Warehouse <span class="required">*</span></label>
                            <select name="warehouse_id" class="form-control" required {{ $model->status != '0' ? 'disabled' : '' }}>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $model->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Product Name <span class="required">*</span></label>
                            <select name="product_id" id="product_id" class="form-control product-select" required {{ $model->status != '0' ? 'disabled' : '' }}>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-stock="{{ (int)$product->factory_stock }}"
                                            data-name="{{ $product->name }}"
                                            {{ $model->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} — [Available: {{ (int)$product->factory_stock }} Cases{{ (int)$product->factory_stock <= 0 ? ' - Out of Stock' : '' }}]
                                    </option>
                                @endforeach
                            </select>
                            <div id="stockBadge" class="mt-2"></div>
                            
                            @error('product_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quantity (Case)<span class="required">*</span></label>
                            <input type="number" 
                                   name="per_case_quantity" 
                                   id="per_case_quantity" 
                                   class="form-control" 
                                   placeholder="Quantity in Case" 
                                   min="1" 
                                   value="{{ old('per_case_quantity', $model->per_case_quantity) }}" 
                                   required 
                                   {{ $model->status != '0' ? 'disabled' : '' }}>
                            <div id="qtyFeedback" class="mt-1"></div>
                            @error('per_case_quantity')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($model->status == '0')
                        <div class="col-md-12 text-end btn-page mt-4">
                            <a href="{{route('manufacture-products')}}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Assignment</button>
                        </div>
                        @else
                        <div class="col-md-12 text-end btn-page mt-4">
                            <a href="{{route('manufacture-products')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
                        @endif
                        
                    </form>

                </div>
            </div>
          
        </div>
        
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>
    
    <script>
    $(document).ready(function() {
        let originalQty = parseInt($('#original_quantity').val()) || 0;
        let originalPid = $('#original_product_id').val();

        function checkEditStock() {
            let select = $('#product_id');
            let selectedOpt = select.find('option:selected');
            let pid = select.val();
            let stock = parseInt(selectedOpt.attr('data-stock')) || 0;
            let qty = parseInt($('#per_case_quantity').val()) || 0;

            if (!pid) {
                $('#stockBadge').html('');
                $('#qtyFeedback').html('');
                return true;
            }

            // If same product, effective available is current available + original already assigned
            let maxAllowed = (pid == originalPid) ? (stock + originalQty) : stock;

            if (stock <= 0 && pid != originalPid) {
                $('#stockBadge').html('<span class="badge bg-danger-subtle text-danger border border-danger p-2"><i class="fa fa-triangle-exclamation me-1"></i> Factory Stock: 0 Cases (Out of Stock)</span>');
            } else {
                $('#stockBadge').html(`<span class="badge bg-success-subtle text-success border border-success p-2"><i class="fa fa-boxes-stacked me-1"></i> Available Factory Stock: <strong>${stock} Cases</strong></span>`);
            }

            if (qty > maxAllowed) {
                $('#per_case_quantity').addClass('is-invalid');
                $('#qtyFeedback').html(`<div class="text-danger small fw-bold mt-1"><i class="fa fa-circle-xmark me-1"></i> Cannot exceed available factory stock (${maxAllowed} Cases max allowed)!</div>`);
                return false;
            } else {
                $('#per_case_quantity').removeClass('is-invalid');
                $('#qtyFeedback').html('');
                return true;
            }
        }

        $('#product_id').on('change', checkEditStock);
        $('#per_case_quantity').on('input keyup change', checkEditStock);
        checkEditStock();

        $('#editAssignForm').on('submit', function(e) {
            if (!checkEditStock()) {
                e.preventDefault();
                if (typeof $.alert === 'function') {
                    $.alert({
                        title: '<span class="text-danger fw-bold"><i class="fa fa-triangle-exclamation me-1"></i> Insufficient Stock</span>',
                        content: 'Requested quantity exceeds available factory stock. Please lower the quantity.',
                        type: 'red'
                    });
                } else {
                    alert('Requested quantity exceeds available factory stock.');
                }
                return false;
            }
        });
    });
    </script>
@endsection
