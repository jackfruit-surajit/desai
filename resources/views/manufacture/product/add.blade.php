@extends('manufacture.layouts.main')

@section('title', 'Product Management')
@section('breadcrumb-item', 'Product Management')
@section('breadcrumb-item-active', 'Assign Products')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />

<style>
    .select2-container {
        width: 100% !important;
    }

    .product-row {
        border: 1px solid #cbd5e1;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        background: #f8fafc;
        position: relative;
        transition: all 0.2s ease;
    }

    .product-row:hover {
        background: #ffffff;
        border-color: #04A9F5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .product-row label, form label {
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .product-row .form-control {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }

    .select2-container--default .select2-selection--single {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-weight: 500 !important;
    }

    .required {
        color: red;
    }

    .stock-badge .badge {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .bg-danger-subtle {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
    }

    .bg-warning-subtle {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .bg-success-subtle {
        background-color: #dcfce7 !important;
        color: #15803d !important;
    }

    .qty-feedback {
        font-size: 12px;
        line-height: 1.4;
    }

    .qty-feedback .text-success {
        color: #15803d !important;
        font-weight: 600;
    }

    .qty-feedback .text-danger {
        color: #b91c1c !important;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><i class="fa fa-paper-plane text-primary me-2"></i>Assign Products to Warehouse</h5>
                    <small class="text-light">Only products with available factory stock can be assigned to warehouses.</small>
                </div>

                <button type="button" class="btn btn-success btn-sm" id="addProduct">
                    <i class="ti ti-plus me-1"></i> Add Another Product
                </button>
            </div>

            <div class="card-body">

                {{-- Flash Message Alerts --}}
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-triangle-exclamation me-1"></i> Error:</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-triangle-exclamation me-1"></i> Please check the following issues:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('manufacture-product-store') }}" id="assignProductForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Warehouse Selection & Assign Date --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-white fw-bold">
                                Select Destination Warehouse <span class="required">*</span>
                            </label>

                            <select name="warehouse_id" class="form-control" required>
                                <option value="">-- Choose Warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}"
                                        {{ old('warehouse_id', request('warehouse_id')) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('warehouse_id')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-white fw-bold">
                                Assign Date <span class="required">*</span>
                            </label>
                            <input type="date" name="assign_date" class="form-control" value="{{ old('assign_date', date('Y-m-d')) }}" required>
                            @error('assign_date')
                                <span class="help-block text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    {{-- Products Container --}}
                    <div id="productContainer">

                        <div class="product-row" data-row-index="0">

                            <div class="row align-items-start">

                                {{-- Product Select --}}
                                <div class="col-md-5">
                                    <label class="form-label text-dark fw-bold">
                                        Product Name <span class="required">*</span>
                                    </label>

                                    <select name="products[0][product_id]"
                                            class="form-control product-select"
                                            required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            @php
                                                $fc = (int)$product->factory_stock;
                                                $fb = (int)($product->factory_bottles ?? 0);
                                                $availLabel = ($fc > 0 && $fb > 0) ? "{$fc} Cases + {$fb} Bottles" : ($fc > 0 ? "{$fc} Cases" : ($fb > 0 ? "{$fb} Bottles" : "0 Cases"));
                                                $isOut = ($fc <= 0 && $fb <= 0);
                                            @endphp
                                            <option value="{{ $product->id }}"
                                                    data-stock="{{ $fc }}"
                                                    data-bottles="{{ $fb }}"
                                                    data-packaging="{{ (int)($product->per_case_quantity ?? 0) }}"
                                                    data-name="{{ $product->name }}"
                                                    {{ (old('products.0.product_id', request('product_id')) == $product->id) ? 'selected' : '' }} {{ $isOut ? 'class=text-danger' : '' }}>
                                                {{ $product->name }} — [Available: {{ $availLabel }}{{ $isOut ? ' - Out of Stock' : '' }}]
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="stock-badge mt-2"></div>
                                </div>


                                {{-- Cases Quantity --}}
                                <div class="col-md-3">
                                    <label class="form-label text-dark fw-bold">
                                        Cases
                                    </label>

                                    <input type="number"
                                           name="products[0][per_case_quantity]"
                                           class="form-control qty-cases-input"
                                           placeholder="Cases"
                                           min="0"
                                           value="{{ old('products.0.per_case_quantity', 0) }}">
                                </div>

                                {{-- Bottles Quantity --}}
                                <div class="col-md-2">
                                    <label class="form-label text-dark fw-bold">
                                        Bottles
                                    </label>

                                    <input type="number"
                                           name="products[0][bottles]"
                                           class="form-control qty-bottles-input"
                                           placeholder="Bottles"
                                           min="0"
                                           value="{{ old('products.0.bottles', 0) }}">
                                </div>


                                {{-- Remove Button --}}
                                <div class="col-md-2">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="button"
                                            class="btn btn-danger remove-product"
                                            style="display:none;">
                                        <i class="ti ti-trash me-1"></i> Remove
                                    </button>
                                </div>

                                <div class="col-12">
                                    <div class="qty-feedback mt-1"></div>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Action Buttons --}}
                    <div class="text-end btn-page mt-4 pt-3 border-top">
                        <a href="{{ route('manufacture-products') }}"
                           class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary" id="btnSubmit">
                            <i class="ti ti-check me-1"></i> Assign Products
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection


@section('scripts')
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>

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
                placeholder: '-- Select Product --',
                allowClear: true,
                width: '100%'
            });
        });
    }

    // Initial Select2
    initializeSelect2();

    function formatCasesBottles(cases, bottles) {
        cases = parseInt(cases) || 0;
        bottles = parseInt(bottles) || 0;
        if (cases > 0 && bottles > 0) return cases + ' Cases + ' + bottles + ' Bottles';
        if (cases > 0) return cases + ' Cases';
        if (bottles > 0) return bottles + ' Bottles';
        return '0 Cases';
    }

    // Template options HTML generated by blade
    const productOptionsHtml = `
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            @php
                $fc = (int)$product->factory_stock;
                $fb = (int)($product->factory_bottles ?? 0);
                $availLabel = ($fc > 0 && $fb > 0) ? "{$fc} Cases + {$fb} Bottles" : ($fc > 0 ? "{$fc} Cases" : ($fb > 0 ? "{$fb} Bottles" : "0 Cases"));
                $isOut = ($fc <= 0 && $fb <= 0);
            @endphp
            <option value="{{ $product->id }}"
                    data-stock="{{ $fc }}"
                    data-bottles="{{ $fb }}"
                    data-packaging="{{ (int)($product->per_case_quantity ?? 0) }}"
                    data-name="{{ $product->name }}"
                    {{ $isOut ? 'class=text-danger' : '' }}>
                {{ $product->name }} — [Available: {{ $availLabel }}{{ $isOut ? ' - Out of Stock' : '' }}]
            </option>
        @endforeach
    `;

    // Add Product Row
    $('#addProduct').click(function () {
        let html = `
        <div class="product-row" data-row-index="${productIndex}">
            <div class="row align-items-start">
                <div class="col-md-5">
                    <label class="form-label text-dark fw-bold">
                        Product Name <span class="required">*</span>
                    </label>
                    <select name="products[${productIndex}][product_id]"
                            class="form-control product-select"
                            required>
                        ${productOptionsHtml}
                    </select>
                    <div class="stock-badge mt-2"></div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-dark fw-bold">
                        Cases
                    </label>
                    <input type="number"
                           name="products[${productIndex}][per_case_quantity]"
                           class="form-control qty-cases-input"
                           placeholder="Cases"
                           min="0"
                           value="0">
                </div>

                <div class="col-md-2">
                    <label class="form-label text-dark fw-bold">
                        Bottles
                    </label>
                    <input type="number"
                           name="products[${productIndex}][bottles]"
                           class="form-control qty-bottles-input"
                           placeholder="Bottles"
                           min="0"
                           value="0">
                </div>

                <div class="col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="button"
                            class="btn btn-danger remove-product">
                        <i class="ti ti-trash me-1"></i> Remove
                    </button>
                </div>

                <div class="col-12">
                    <div class="qty-feedback mt-1"></div>
                </div>
            </div>
        </div>
        `;

        $('#productContainer').append(html);
        initializeSelect2();
        productIndex++;
        updateRemoveButtons();
        validateAllStock();
    });

    // Remove Product Row
    $(document).on('click', '.remove-product', function () {
        $(this).closest('.product-row').remove();
        updateRemoveButtons();
        validateAllStock();
    });

    // Toggle remove button visibility
    function updateRemoveButtons() {
        let rows = $('.product-row');
        if (rows.length <= 1) {
            rows.find('.remove-product').hide();
        } else {
            rows.find('.remove-product').show();
        }
    }

    // Core validation function
    function validateAllStock() {
        let allValid = true;
        let productTotals = {};

        // Pass 1: Sum quantities per product across all rows
        $('.product-row').each(function () {
            let row = $(this);
            let productId = row.find('.product-select').val();
            let cases = parseInt(row.find('.qty-cases-input').val()) || 0;
            let bottles = parseInt(row.find('.qty-bottles-input').val()) || 0;

            if (productId) {
                if (!productTotals[productId]) productTotals[productId] = {cases: 0, bottles: 0};
                productTotals[productId].cases += cases;
                productTotals[productId].bottles += bottles;
            }
        });

        // Pass 2: Validate each row individually and combined
        $('.product-row').each(function () {
            let row = $(this);
            let select = row.find('.product-select');
            let selectedOption = select.find('option:selected');
            let productId = select.val();
            let stockBadge = row.find('.stock-badge');
            let casesInput = row.find('.qty-cases-input');
            let bottlesInput = row.find('.qty-bottles-input');
            let qtyFeedback = row.find('.qty-feedback');

            let enteredCases = parseInt(casesInput.val() === '' ? 0 : casesInput.val());
            let enteredBottles = parseInt(bottlesInput.val() === '' ? 0 : bottlesInput.val());

            if (!productId) {
                stockBadge.html('');
                qtyFeedback.html('');
                casesInput.removeClass('is-invalid is-valid');
                bottlesInput.removeClass('is-invalid is-valid');
                return;
            }

            let availCases = parseInt(selectedOption.attr('data-stock')) || 0;
            let availBottles = parseInt(selectedOption.attr('data-bottles')) || 0;
            let packaging = parseInt(selectedOption.attr('data-packaging')) || 0;
            let productName = selectedOption.attr('data-name') || 'Selected Product';
            let availText = formatCasesBottles(availCases, availBottles);
            let packNote = packaging > 0 ? ` (${packaging} Bottles/Case)` : '';

            // Render stock badge
            if (availCases <= 0 && availBottles <= 0) {
                stockBadge.html(`<span class="badge bg-danger-subtle text-danger border border-danger p-2"><i class="fa fa-triangle-exclamation me-1"></i> Factory Stock: <strong>0 Cases</strong> (Out of Stock)</span>`);
            } else if (availCases <= 50) {
                stockBadge.html(`<span class="badge bg-warning-subtle text-dark border border-warning p-2"><i class="fa fa-boxes-stacked me-1"></i> Factory Stock: <strong>${availText}</strong>${packNote} (Low Stock)</span>`);
            } else {
                stockBadge.html(`<span class="badge bg-success-subtle text-success border border-success p-2"><i class="fa fa-boxes-stacked me-1"></i> Factory Stock: <strong>${availText}</strong>${packNote}</span>`);
            }

            // Out of stock
            if (availCases <= 0 && availBottles <= 0) {
                casesInput.addClass('is-invalid').removeClass('is-valid');
                bottlesInput.addClass('is-invalid').removeClass('is-valid');
                qtyFeedback.html(`<div class="text-danger small fw-bold mt-1"><i class="fa fa-circle-xmark me-1"></i> <strong>${productName}</strong> is out of stock in factory.</div>`);
                allValid = false;
                return;
            }

            if (isNaN(enteredCases) || enteredCases < 0 || isNaN(enteredBottles) || enteredBottles < 0) {
                casesInput.addClass('is-invalid').removeClass('is-valid');
                bottlesInput.addClass('is-invalid').removeClass('is-valid');
                qtyFeedback.html(`<div class="text-danger small fw-bold mt-1">Quantities cannot be negative.</div>`);
                allValid = false;
                return;
            }

            if (enteredCases === 0 && enteredBottles === 0) {
                casesInput.removeClass('is-valid is-invalid');
                bottlesInput.removeClass('is-valid is-invalid');
                qtyFeedback.html(`<div class="text-muted small mt-1">Available to assign: <strong>${availText}</strong></div>`);
                allValid = false;
                return;
            }

            let totReq = productTotals[productId] || {cases: 0, bottles: 0};
            let reqText = formatCasesBottles(enteredCases, enteredBottles);
            let totReqText = formatCasesBottles(totReq.cases, totReq.bottles);

            if (packaging > 0) {
                let availTotalBottles = (availCases * packaging) + availBottles;
                let rowTotalBottles = (enteredCases * packaging) + enteredBottles;
                let combinedTotalBottles = (totReq.cases * packaging) + totReq.bottles;

                if (rowTotalBottles > availTotalBottles) {
                    casesInput.addClass('is-invalid').removeClass('is-valid');
                    bottlesInput.addClass('is-invalid').removeClass('is-valid');
                    qtyFeedback.html(`<div class="text-danger small fw-bold mt-1"><i class="fa fa-circle-xmark me-1"></i> Entered quantity (${reqText}) exceeds available factory stock (${availText})!</div>`);
                    allValid = false;
                    return;
                }

                if (combinedTotalBottles > availTotalBottles) {
                    casesInput.addClass('is-invalid').removeClass('is-valid');
                    bottlesInput.addClass('is-invalid').removeClass('is-valid');
                    qtyFeedback.html(`<div class="text-danger small fw-bold mt-1"><i class="fa fa-circle-xmark me-1"></i> Total requested across rows (${totReqText}) exceeds available factory stock (${availText})!</div>`);
                    allValid = false;
                    return;
                }

                let remTotal = availTotalBottles - combinedTotalBottles;
                let remCases = Math.floor(remTotal / packaging);
                let remBottles = remTotal % packaging;
                casesInput.removeClass('is-invalid').addClass('is-valid');
                bottlesInput.removeClass('is-invalid').addClass('is-valid');
                qtyFeedback.html(`<div class="text-success small mt-1"><i class="fa fa-circle-check me-1"></i> Assigning <strong>${reqText}</strong>. Remaining factory stock: <strong>${formatCasesBottles(remCases, remBottles)}</strong>.</div>`);
            } else {
                if (totReq.cases > availCases || totReq.bottles > availBottles) {
                    casesInput.addClass('is-invalid').removeClass('is-valid');
                    bottlesInput.addClass('is-invalid').removeClass('is-valid');
                    qtyFeedback.html(`<div class="text-danger small fw-bold mt-1"><i class="fa fa-circle-xmark me-1"></i> Requested (${totReqText}) exceeds available (${availText})!</div>`);
                    allValid = false;
                    return;
                }
                casesInput.removeClass('is-invalid').addClass('is-valid');
                bottlesInput.removeClass('is-invalid').addClass('is-valid');
                qtyFeedback.html(`<div class="text-success small mt-1"><i class="fa fa-circle-check me-1"></i> Assigning <strong>${reqText}</strong>. Remaining: <strong>${formatCasesBottles(availCases - totReq.cases, availBottles - totReq.bottles)}</strong>.</div>`);
            }
        });

        return allValid;
    }

    // Event listeners
    $(document).on('change', '.product-select', function () {
        validateAllStock();
    });

    $(document).on('input keyup change', '.qty-cases-input, .qty-bottles-input', function () {
        validateAllStock();
    });

    // Form submission validation
    $('#assignProductForm').on('submit', function (e) {
        let rows = $('.product-row');
        if (rows.length === 0) {
            e.preventDefault();
            alertMessage('Error', 'Please add at least one product before submitting.');
            return false;
        }

        let isWarehouseSelected = $('select[name="warehouse_id"]').val();
        if (!isWarehouseSelected) {
            e.preventDefault();
            alertMessage('Warehouse Required', 'Please select a destination warehouse.');
            $('select[name="warehouse_id"]').focus();
            return false;
        }

        let validationError = null;
        let invalidInput = null;

        rows.each(function () {
            let row = $(this);
            let select = row.find('.product-select');
            let selectedOption = select.find('option:selected');
            let productId = select.val();
            let casesInput = row.find('.qty-cases-input');
            let bottlesInput = row.find('.qty-bottles-input');
            let cases = parseInt(casesInput.val()) || 0;
            let bottles = parseInt(bottlesInput.val()) || 0;

            if (!productId) {
                validationError = 'Please select a product for all rows.';
                invalidInput = select;
                return false;
            }

            let availCases = parseInt(selectedOption.attr('data-stock')) || 0;
            let availBottles = parseInt(selectedOption.attr('data-bottles')) || 0;
            let packaging = parseInt(selectedOption.attr('data-packaging')) || 0;
            let productName = selectedOption.attr('data-name') || 'Selected Product';

            if (availCases <= 0 && availBottles <= 0) {
                validationError = `"${productName}" is currently OUT OF STOCK in factory. You cannot assign it to a warehouse.`;
                invalidInput = casesInput;
                return false;
            }

            if (cases <= 0 && bottles <= 0) {
                validationError = `Please enter at least 1 Case or 1 Bottle for "${productName}".`;
                invalidInput = casesInput;
                return false;
            }

            if (packaging > 0) {
                if (((cases * packaging) + bottles) > ((availCases * packaging) + availBottles)) {
                    validationError = `Requested quantity for "${productName}" (${formatCasesBottles(cases, bottles)}) exceeds available factory stock (${formatCasesBottles(availCases, availBottles)}).`;
                    invalidInput = casesInput;
                    return false;
                }
            } else if (cases > availCases || bottles > availBottles) {
                validationError = `Requested quantity for "${productName}" exceeds available factory stock.`;
                invalidInput = casesInput;
                return false;
            }
        });

        if (validationError) {
            e.preventDefault();
            validateAllStock();
            if (invalidInput) {
                invalidInput.focus();
            }
            alertMessage('Insufficient Factory Stock', validationError);
            return false;
        }

        let isValid = validateAllStock();
        if (!isValid) {
            e.preventDefault();
            alertMessage('Validation Failed', 'Please fix the quantity errors highlighted in red before submitting.');
            return false;
        }
    });

    function alertMessage(title, message) {
        if (typeof $.alert === 'function') {
            $.alert({
                title: `<span class="text-danger fw-bold"><i class="fa fa-triangle-exclamation me-1"></i> ${title}</span>`,
                content: message,
                type: 'red',
                theme: 'modern'
            });
        } else {
            alert(title + ': ' + message);
        }
    }

    // Run initial validation on page load
    validateAllStock();

});
</script>
@endsection
