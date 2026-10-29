@extends('admin.layouts.main')

@section('title', 'Staff Management')
@section('breadcrumb-item', 'Staff Management')

@section('breadcrumb-item-active', 'Edit Sales Executive')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Update Sales Executive {{$model->name}}</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('staff-update')}}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $model->id }}">
                        <input type="hidden" name="role" value="2">

                        <div class="col-md-6">
                            <label for="" class="form-label">Executive Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Executive Name" name="name" value="{{ (old('name')!='') ? old('name') : $model->name}}" >
                            @if ($errors->has('name'))
                                <span class="help-block"> {{ $errors->first('name') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Email<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ (old('email')!='') ? old('email') : $model->email}}" >
                            @if ($errors->has('email'))
                                <span class="help-block"> {{ $errors->first('email') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Contact No<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Contact No" name="phone" value="{{ (old('phone')!='') ? old('phone') : $model->phone}}" >
                            @if ($errors->has('phone'))
                                <span class="help-block"> {{ $errors->first('phone') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6" id="area_details">
                            <label for="" class="form-label">Area<span class="required">*</span> (Maximum 5 area)</label>
                            @php
                                $savedOrder = old('area_order') ? explode(',', old('area_order')) : (old('area_id') ?? ($selected_areas ?? []));
                                $savedOrder = array_values(array_filter(array_map('strval', (array)$savedOrder)));
                                $areaKeyed = $areas->keyBy(function($item) { return (string)$item->id; });
                                $orderedAreas = collect();
                                foreach ($savedOrder as $sId) {
                                    if ($areaKeyed->has($sId)) {
                                        $orderedAreas->push($areaKeyed->get($sId));
                                    }
                                }
                                foreach ($areas as $a) {
                                    if (!in_array((string)$a->id, $savedOrder)) {
                                        $orderedAreas->push($a);
                                    }
                                }
                            @endphp
                            <input type="hidden" name="area_order" id="area_order" value="{{ implode(',', $savedOrder) }}">
                            <select class="form-control select2" name="area_id[]" id= "area_id" multiple>
                                <option value="" class="option selected focus" disabled>Select Area</option>
                                @forelse($orderedAreas as $area)
                                    <option value="{{ $area->id }}" {{ in_array((string)$area->id, $savedOrder) ? 'selected' : '' }}>{{$area->area_name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('area_id'))
                                <span class="help-block"> {{ $errors->first('area_id') }} </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="inputImage" class="form-label">Executive Photo</label>
                            <input type="file" class="form-control" name="image" >
                            @if ($errors->has('image'))
                                <span class="help-block"> {{ $errors->first('image') }} </span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(!empty($model->image))
                                <a href="{{!empty($model->image)?URL::asset(asset_path('uploads/staff/'.$model->image)):'#'}}" class="btn btn-xs btn-primary pull-left mt-4" target="_blank"><i class="fa fa-eye"></i>View Image</a>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <label for="" class="form-label">Password</label>
                            <input type="text" class="form-control" placeholder="Password" name="password" id="password" value="{{ (old('password')!='') ? old('password') : ''}}" >
                            @if ($errors->has('password'))
                                <span class="help-block"> {{ $errors->first('password') }} </span>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <label for="" class="form-label"></label>
                            <br/>
                            <a href="javascript:;" onclick="passwordGenerator();" class="btn btn-success" > Generate</a>
                        </div>
                        <div class="col-md-1">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" {{ ($model->status == '1') ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" {{ ($model->status == '0') ? 'checked' : '' }}>
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

            var $areaSelect = $('#area_id');
            var initialVal = $('#area_order').val();
            var selectedAreaOrder = initialVal ? initialVal.split(',').filter(Boolean) : [];

            if (selectedAreaOrder.length === 0) {
                $areaSelect.find('option:selected').each(function () {
                    var val = $(this).val();
                    if (val && selectedAreaOrder.indexOf(val) === -1) {
                        selectedAreaOrder.push(val);
                    }
                });
                $('#area_order').val(selectedAreaOrder.join(','));
            }

            $areaSelect.on('select2:select', function (e) {
                var id = String(e.params.data.id);
                selectedAreaOrder = selectedAreaOrder.filter(function(x) { return x !== id; });
                selectedAreaOrder.push(id);
                $('#area_order').val(selectedAreaOrder.join(','));

                var $opt = $(this).find('option[value="' + id + '"]');
                if ($opt.length) {
                    $opt.detach();
                    $opt.prop('selected', true);
                    $(this).append($opt);
                }
            });

            $areaSelect.on('select2:unselect', function (e) {
                var id = String(e.params.data.id);
                selectedAreaOrder = selectedAreaOrder.filter(function(x) { return x !== id; });
                $('#area_order').val(selectedAreaOrder.join(','));
            });

            $areaSelect.closest('form').on('submit', function () {
                $.each(selectedAreaOrder, function (index, id) {
                    var $opt = $areaSelect.find('option[value="' + id + '"]');
                    if ($opt.length) {
                        $opt.detach();
                        $opt.prop('selected', true);
                        $areaSelect.append($opt);
                    }
                });
                $('#area_order').val(selectedAreaOrder.join(','));
            });
        });
        function passwordGenerator(){
            var length = 8,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            $('#password').val(retVal);
        }
        $(document).ready(function(){
            
        });

    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
