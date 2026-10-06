@extends('warehouse.layouts.main')

@section('title', 'Invoice Management')
@section('breadcrumb-item', 'Invoice Management')
@section('breadcrumb-item-active', 'Assign Products')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
    .select2-container {
        width: 100% !important;
    }

    .product-row {
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .remove-product {
        margin-top: 30px;
    }

    .required {
        color: red;
    }
    
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assign Multiple Products To Warehouse</h5>

                <button type="button" class="btn btn-success btn-sm" id="addProduct">
                    <i class="ti ti-plus"></i> Add Product
                </button>
            </div>

            <div class="card-body">
                
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                <form method="POST" action="{{ route('warehouse-invoice-store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Manufacturer --}}
                    <div class="row mb-4">

                        <div class="col-md-12">
                            <label class="form-label">Customer / Shop Name <span class="required">*</span></label>
                            <input type="text" name="shop_name" class="form-control" placeholder="Shop Name / Customer Name" required>
                            @error('shop_name')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Email ID" required>
                            @error('email')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mt-2">
                            <label class="form-label">Contact No <span class="required">*</span></label>
                            <input type="text" name="contact_no" class="form-control" placeholder="Contact No" required>
                            @error('contact_no')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label class="form-label">GST No<span class="required">*</span></label>
                            <input type="text" name="gst_no" class="form-control" placeholder="GST No" required>
                            @error('gst_no')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label class="form-label">PAN No<span class="required">*</span></label>
                            <input type="text" name="pan_no" class="form-control" placeholder="PAN NO" required>
                            @error('pan_no')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mt-2">
                            <label class="form-label">State<span class="required">*</span></label>
                            <input type="text" name="state" class="form-control" placeholder="State" required>
                            @error('state')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>
                    
                    <hr class="text-white">


                    {{-- Products --}}
                    <div id="productContainer">

                        <div class="product-row">

                            <div class="row">

                                {{-- Product --}}
                                <div class="col-md-5">

                                    <label class="form-label text-dark">
                                        Product Name <span class="required">*</span>
                                    </label>

                                    <select name="products[0][product_id]"
                                            class="form-control product-select"
                                            required>

                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach($products as $product)

                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Per Case Quantity --}}
                                <div class="col-md-3">
                                    <label class="form-label text-dark">Quantity<span class="required">*</span></label>
                                    <input type="number" name="products[0][quantity]" class="form-control" placeholder="Quantity" min="1" required>
                                </div>
                                
                                <div class="col-md-2">

                                    <label class="form-label text-dark">Unit<span class="required">*</span></label>
                                    <select class="form-control" name="products[0][unit]">
                                        <option value="case">Case</option>
                                        <option value="bottle">Bottle</option>
                                    </select>

                                </div>
                                
                                


                                {{-- Remove --}}
                                <div class="col-md-2">

                                    <button type="button"
                                            class="btn btn-danger remove-product"
                                            style="display:none;">

                                        <i class="ti ti-trash"></i>
                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="text-end btn-page mt-3">

                        <a href="{{ route('warehouse-invoice') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="ti ti-check"></i>
                            Generate Invoice
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection


@section('scripts')

<script>

$(document).ready(function () {

    let productIndex = 1;

    // Initialize Select2
    function initializeSelect2() {

        $('.product-select').each(function () {

            if ($(this).hasClass('select2-hidden-accessible')) {
                return;
            }

            $(this).select2({
                placeholder: 'Select Product',
                allowClear: true,
                width: '100%'
            });

        });

    }


    // Initial Select2
    initializeSelect2();


    // Add Product
    $('#addProduct').click(function () {

        let html = `

        <div class="product-row">

            <div class="row">

                <div class="col-md-5">

                    <label class="form-label text-dark">
                        Product Name
                        <span class="required">*</span>
                    </label>

                    <select name="products[${productIndex}][product_id]"
                            class="form-control product-select"
                            required>

                        <option value="">
                            Select Product
                        </option>

                        @foreach($products as $product)

                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label text-dark">
                        Quantity
                        <span class="required">*</span>
                    </label>

                    <input type="number"
                           name="products[${productIndex}][quantity]"
                           class="form-control"
                           placeholder="Quantity"
                           min="1"
                           required>

                </div>
                
                <div class="col-md-2">
                    <label class="form-label text-dark">Unit<span class="required">*</span></label>
                    <select class="form-control" name="products[${productIndex}][unit]">
                        <option value="case">Case</option>
                        <option value="bottle">Bottle</option>
                    </select>
                </div>


                <div class="col-md-2">

                    <button type="button"
                            class="btn btn-danger remove-product">

                        <i class="ti ti-trash"></i>
                        Remove

                    </button>

                </div>

            </div>

        </div>

        `;

        $('#productContainer').append(html);
        
        // Initialize Select2 for newly added field
        initializeSelect2();

        productIndex++;

        updateRemoveButtons();

    });


    // Remove Product
    $(document).on('click', '.remove-product', function () {

        $(this).closest('.product-row').remove();

        updateRemoveButtons();

    });


    // Hide remove button when only one product exists
    function updateRemoveButtons() {

        let rows = $('.product-row');

        if (rows.length === 1) {

            rows.find('.remove-product').hide();

        } else {

            rows.find('.remove-product').show();

        }

    }

});

</script>

@endsection