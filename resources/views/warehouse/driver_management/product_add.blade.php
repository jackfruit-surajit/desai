@extends('warehouse.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')
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
                <h5 class="mb-0">Assign Multiple Products To Driver</h5>

                <button type="button" class="btn btn-success btn-sm" id="addProduct">
                    <i class="ti ti-plus"></i> Add Product
                </button>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('driver-product-store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Driver --}}
                    <div class="row mb-4">

                        <div class="col-md-6">
                            <label class="form-label">
                                Driver <span class="required">*</span>
                            </label>

                            <select name="driver_id" class="form-control select2" required>

                                <option value="">Select Driver</option>

                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('driver_id')
                                <span class="help-block text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                    </div>


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
                                <div class="col-md-4">

                                    <label class="form-label text-dark">
                                        Quantity (Case)
                                        <span class="required">*</span>
                                    </label>

                                    <input type="number"
                                           name="products[0][per_case_quantity]"
                                           class="form-control"
                                           placeholder="Quantity in Case"
                                           min="1"
                                           required>

                                </div>
                                
                                


                                {{-- Remove --}}
                                <div class="col-md-3">

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

                        <a href="{{ route('manufacture-products') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="ti ti-check"></i>
                            Assign Products
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


                <div class="col-md-4">

                    <label class="form-label text-dark">
                        Per Case Quantity
                        <span class="required">*</span>
                    </label>

                    <input type="number"
                           name="products[${productIndex}][per_case_quantity]"
                           class="form-control"
                           placeholder="Quantity in Case"
                           min="1"
                           required>

                </div>


                <div class="col-md-3">

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


    $('.select2').select2();
  

</script>

@endsection