@extends('admin.layouts.main')

@section('title', 'Area Management')
@section('breadcrumb-item', 'Area Management')
@section('breadcrumb-item-active', 'Edit Area')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa fa-edit text-primary me-2"></i> Edit Area</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="row g-3" method="post" action="{{ route('area-update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $model->id }}">

                        {{-- State Field --}}
                        <div class="col-md-6">
                            <label for="state_id" class="form-label font-weight-bold">
                                State <span class="required text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="state_id" id="state_id" required>
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ (old('state_id', $model->state_id) == $state->id) ? 'selected' : '' }}>
                                        {{ $state->state_name }} {{ $state->state_code ? '('.$state->state_code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('state_id'))
                                <span class="text-danger small">{{ $errors->first('state_id') }}</span>
                            @endif
                        </div>

                        {{-- Area Name Field --}}
                        <div class="col-md-6">
                            <label for="area_name" class="form-label font-weight-bold">
                                Area Name <span class="required text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="Enter Area Name" name="area_name" id="area_name" value="{{ old('area_name', $model->area_name) }}" required>
                            @if ($errors->has('area_name'))
                                <span class="text-danger small">{{ $errors->first('area_name') }}</span>
                            @endif
                        </div>

                        {{-- Status Field --}}
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">
                                Status <span class="required text-danger">*</span> :
                            </label>
                            <br>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ old('status', $model->status) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ old('status', $model->status) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="statusInactive">
                                    Inactive
                                </label>
                            </div>

                            @if ($errors->has('status'))
                                <br>
                                <span class="text-danger small">{{ $errors->first('status') }}</span>
                            @endif
                        </div>

                        <div class="col-md-12 text-end btn-page mt-4">
                            <a href="{{ route('areas') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update Area
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Select State --",
            allowClear: true
        });
    });
</script>
@endsection

