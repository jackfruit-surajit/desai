@extends('admin.layouts.main')

@section('title', 'State Management')
@section('breadcrumb-item', 'State Management')
@section('breadcrumb-item-active', 'Edit State')

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa fa-edit text-primary me-2"></i> Edit State</h5>
                </div>
                <div class="card-body">

                    @if (session('error_msg'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error_msg') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="row g-3" method="post" action="{{ route('state-update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $model->id }}">

                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">
                                State Name <span class="required text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="Enter State Name" name="state_name" value="{{ old('state_name', $model->state_name) }}" required>
                            @if ($errors->has('state_name'))
                                <span class="text-danger small">{{ $errors->first('state_name') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">
                                State Code (Optional)
                            </label>
                            <input type="text" class="form-control" placeholder="Enter State Code" name="state_code" value="{{ old('state_code', $model->state_code) }}">
                            @if ($errors->has('state_code'))
                                <span class="text-danger small">{{ $errors->first('state_code') }}</span>
                            @endif
                        </div>

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
                            <a href="{{ route('states') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update State
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

