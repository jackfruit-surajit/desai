@extends('manufacture.layouts.main')

@section('title', 'Edit Factory Stock')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Edit Factory Stock')

@section('css')
<link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
<style>
    .form-label {
        color: #0f172a !important;
        font-weight: 600 !important;
    }
    .form-control {
        color: #0f172a !important;
        border: 1px solid #cbd5e1;
    }
    .form-control:disabled, 
    .form-control[readonly] {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        opacity: 1 !important;
        font-weight: 600 !important;
        border: 1px solid #94a3b8 !important;
        -webkit-text-fill-color: #0f172a !important;
    }
    .diff-box {
        border-radius: 8px;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }
    .diff-box.increase {
        background-color: #dcfce7;
        border: 1px solid #86efac;
        color: #15803d;
    }
    .diff-box.decrease {
        background-color: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
    }
    .diff-box.neutral {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #475569;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <a href="{{ route('manufacture-stock') }}" class="btn btn-outline-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back to Factory Stock
        </a>
    </div>

    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error_msg'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-triangle-exclamation me-1"></i> {{ session('error_msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Left Column: Product Information -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fa fa-box me-1"></i> Product Overview
                </h5>
            </div>
            <div class="card-body">
                <h4 class="fw-bold text-dark mb-1">{{ $product->name }}</h4>
                <div class="text-muted small mb-3">Product ID: #{{ $product->id }}</div>

                <div class="list-group list-group-flush mb-3">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Packaging</span>
                        <span class="fw-bold text-dark">{{ $product->per_case_quantity ?? 0 }} Bottles / Case</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Per Case Price</span>
                        <span class="fw-bold text-dark">₹{{ number_format((float)($product->per_case_price ?? 0), 2) }}</span>
                    </div>
                    @if(!empty($product->per_bottle_price))
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Per Bottle Price</span>
                        <span class="fw-bold text-dark">₹{{ number_format((float)$product->per_bottle_price, 2) }}</span>
                    </div>
                    @endif
                    @if(!empty($product->hsn_code))
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">HSN Code</span>
                        <span class="fw-bold text-dark">{{ $product->hsn_code }}</span>
                    </div>
                    @endif
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Dispatched</span>
                        <span class="badge bg-info text-dark" style="font-size:13px;">{{ $totalDispatched ?? 0 }} Cases</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Current Factory Stock</span>
                        @if($currentStock <= 0)
                            <span class="badge bg-danger" style="font-size:13px;">0 Cases (Out of Stock)</span>
                        @elseif($currentStock <= 50)
                            <span class="badge bg-warning text-dark" style="font-size:13px;">{{ $currentStock }} Cases (Low Stock)</span>
                        @else
                            <span class="badge bg-success" style="font-size:13px;">{{ $currentStock }} Cases</span>
                        @endif
                    </div>
                </div>

                <div class="alert alert-info border-0 mb-0" style="font-size: 13px;">
                    <i class="fa fa-info-circle me-1"></i> Editing stock directly updates the available in-hand inventory at the factory and logs an audit record under <strong>Adjustment</strong>.
                </div>
            </div>
        </div>

        @if(isset($recentLogs) && count($recentLogs) > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa fa-history me-1"></i> Recent Stock Movement
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                    <tr>
                                        <td>{{ date('d M, Y', strtotime($log->created_at)) }}</td>
                                        <td>
                                            @if($log->type == 'production')
                                                <span class="badge bg-success">Production</span>
                                            @elseif($log->type == 'dispatch')
                                                <span class="badge bg-info text-dark">Dispatch</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Adjustment</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ $log->type == 'dispatch' ? '-' : ($log->type == 'production' ? '+' : '') }}{{ $log->quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Edit Stock Form -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fa fa-edit text-warning me-1"></i> Adjust / Edit Factory Stock
                        </h5>
                        <small class="text-muted">Set the accurate in-hand physical stock quantity for this product</small>
                    </div>
                    <span class="badge bg-light text-dark border">
                        Current: <strong>{{ $currentStock }} Cases</strong>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('manufacture-stock-update') }}" method="POST" id="editStockForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" id="current_stock_val" value="{{ $currentStock }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Current Stock in Hand (Cases)</label>
                            <input type="text" class="form-control" value="{{ $currentStock }} Cases" readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fs-6">Updated / Correct Stock Quantity (Cases) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="stock" 
                                   id="stock_input" 
                                   class="form-control form-control-lg fw-bold text-primary" 
                                   min="0" 
                                   step="1" 
                                   value="{{ old('stock', $currentStock) }}" 
                                   placeholder="Enter updated stock in cases" 
                                   required>
                            <small class="text-muted">Enter the actual count of cases physically present in factory warehouse.</small>
                        </div>

                        <!-- Dynamic Stock Difference Calculator -->
                        <div class="col-12">
                            <div class="diff-box neutral text-center" id="diffBox">
                                <span class="fw-bold" id="diffText">
                                    <i class="fa fa-equals me-1"></i> No change (Stock will remain {{ $currentStock }} Cases)
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date of Adjustment</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Batch / Reference Number (Optional)</label>
                            <input type="text" name="batch_no" class="form-control" placeholder="e.g. ADJ-{{ date('Ymd') }}-01" value="{{ old('batch_no') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Reason for Adjustment / Preset</label>
                            <select class="form-select mb-2" id="reasonPreset">
                                <option value="">-- Select a Common Reason (Optional) --</option>
                                <option value="Physical Inventory Count / Audit Correction">Physical Inventory Count / Audit Correction</option>
                                <option value="Damaged / Broken / Leaked Bottles">Damaged / Broken / Leaked Bottles</option>
                                <option value="Production Entry Correction">Production Entry Correction</option>
                                <option value="Quality Inspection Rejection">Quality Inspection Rejection</option>
                                <option value="Returned from Transit / Warehouse">Returned from Transit / Warehouse</option>
                                <option value="Other">Other Reason (Specify below)</option>
                            </select>
                            <textarea name="note" id="noteInput" class="form-control" rows="3" placeholder="Enter reason or notes for this stock adjustment...">{{ old('note') }}</textarea>
                            <small class="text-muted">This note will appear in the Stock Movement Logs.</small>
                        </div>

                        <div class="col-12 text-end pt-3 border-top">
                            <a href="{{ route('manufacture-stock') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                                <i class="fa fa-save me-1"></i> Update Stock
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var currentStock = parseInt($('#current_stock_val').val()) || 0;
    var $stockInput = $('#stock_input');
    var $diffBox = $('#diffBox');
    var $diffText = $('#diffText');

    function calculateDiff() {
        var rawVal = $stockInput.val();
        if (rawVal === '') {
            $diffBox.attr('class', 'diff-box neutral text-center');
            $diffText.html('<i class="fa fa-question-circle me-1"></i> Please enter a valid stock quantity');
            return;
        }

        var newStock = parseInt(rawVal);
        if (isNaN(newStock) || newStock < 0) {
            $diffBox.attr('class', 'diff-box decrease text-center');
            $diffText.html('<i class="fa fa-triangle-exclamation me-1"></i> Stock quantity cannot be negative');
            return;
        }

        var diff = newStock - currentStock;

        if (diff > 0) {
            $diffBox.attr('class', 'diff-box increase text-center');
            $diffText.html('<i class="fa fa-arrow-up me-1"></i> <strong>Stock will INCREASE by +' + diff + ' Cases</strong> (from ' + currentStock + ' to ' + newStock + ' Cases)');
        } else if (diff < 0) {
            $diffBox.attr('class', 'diff-box decrease text-center');
            $diffText.html('<i class="fa fa-arrow-down me-1"></i> <strong>Stock will DECREASE by ' + diff + ' Cases</strong> (from ' + currentStock + ' to ' + newStock + ' Cases)');
        } else {
            $diffBox.attr('class', 'diff-box neutral text-center');
            $diffText.html('<i class="fa fa-equals me-1"></i> <strong>No Change in Stock</strong> (remains ' + currentStock + ' Cases)');
        }
    }

    $stockInput.on('input keyup change', calculateDiff);
    calculateDiff();

    // Preset reason selector
    $('#reasonPreset').on('change', function() {
        var val = $(this).val();
        if (val && val !== 'Other') {
            var currentNote = $('#noteInput').val();
            if (currentNote === '' || currentNote === 'Physical Inventory Count / Audit Correction' || currentNote === 'Damaged / Broken / Leaked Bottles' || currentNote === 'Production Entry Correction' || currentNote === 'Quality Inspection Rejection' || currentNote === 'Returned from Transit / Warehouse') {
                $('#noteInput').val(val);
            }
        }
    });
});
</script>
@endsection

