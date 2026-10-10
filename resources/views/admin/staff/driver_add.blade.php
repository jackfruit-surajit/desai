@extends('admin.layouts.main')

@section('title', 'Staff Management')
@section('breadcrumb-item', 'Staff Management')

@section('breadcrumb-item-active', 'Add Driver')

@section('css')
<style>
   .sortable-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.sortable-table th,
.sortable-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

.sortable-table th {
    background: #f5f5f5;
}

.sortable-table tbody tr {
    background: #fff;
    cursor: move;
}

.sortable-table tbody tr:hover {
    background: #f8f8f8;
}

.sortable-table tbody tr.dragging {
    opacity: 0.5;
    background: #eee;
}
</style>
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Add New Driver</h5>
                </div>
                <div class="card-body">


                    <form class="row g-3" method="post" action="{{route('user-store')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role" value="3">

                        <div class="col-md-6">
                            <label for="" class="form-label">Driver Name<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Driver Name" name="name" value="{{ (old('name')!='') ? old('name') : ''}}" >
                            @if ($errors->has('name'))
                                <span class="help-block"> {{ $errors->first('name') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Email<span class="required">*</span></label>
                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ (old('email')!='') ? old('email') : ''}}" >
                            @if ($errors->has('email'))
                                <span class="help-block"> {{ $errors->first('email') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputPhone" class="form-label">Contact No<span class="required">*</span></label>
                            <input type="text" class="form-control" placeholder="Contact No" name="phone" value="{{ (old('phone')!='') ? old('phone') : ''}}" >
                            @if ($errors->has('phone'))
                                <span class="help-block"> {{ $errors->first('phone') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6" id="vehicle_details">
                            <label for="" class="form-label">Transport Vehicle<span class="required">*</span></label>
                            <select class="form-control select2" name="vehicle_id" id= "vehicle_id">
                                <option class="option selected focus" selected disabled>Select</option>
                                @forelse($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{$vehicle->vehicle_name}} - {{$vehicle->vehicle_no}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('vehicle_id'))
                                <span class="help-block"> {{ $errors->first('vehicle_id') }} </span>
                            @endif
                        </div>
                        <div class="col-md-6" id="area_details">
                            <label for="" class="form-label">Assign Area<span class="required">*</span></label>
                            <select class="form-control select2" name="area_id" id= "area_id">
                                <option class="option selected focus" selected disabled>Select Area</option>
                                @forelse($areas as $area)
                                    <option value="{{ $area->id }}">{{$area->area_name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('area_id'))
                                <span class="help-block"> {{ $errors->first('area_id') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6" id="warehouse_details">
                            <label for="" class="form-label">Assign Warehouse<span class="required">*</span></label>
                            <select class="form-control select2" name="warehouse_id" id= "warehouse_id">
                                <option class="option selected focus" selected disabled>Select Warehouse</option>
                                @forelse($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{$warehouse->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('warehouse_id'))
                                <span class="help-block"> {{ $errors->first('warehouse_id') }} </span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label for="inputImage" class="form-label">Driver Photo</label>
                            <input type="file" class="form-control" name="image" >
                            @if ($errors->has('image'))
                                <span class="help-block"> {{ $errors->first('image') }} </span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Password<span class="required">*</span></label>
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

                        
                        <div class="col-md-6">
                            <label class="form-label text-white">
                                Status <span class="required">*</span> :
                            </label>
                            <br>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                                <label class="form-check-label text-white" for="statusActive">
                                    Active
                                </label>
                            </div>
                        
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
                                <label class="form-check-label text-white" for="statusInactive">
                                    Inactive
                                </label>
                            </div>
                        
                            @if ($errors->has('status'))
                                <br>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <hr class="text-white">
                        <div class="col-md-12 d-none" id="roads_container">
                            <h5 class="mt-4 text-white">Route / Road List</h5>
                            <table class="sortable-table" id="sortableTable">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th width="150">Route / Road Name</th>
                                        <th width="150">Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div id="hidden_road_inputs"></div>
                        </div>

                        
                        <div class="col-md-12 text-end btn-page mt-4">
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
    
        $(document).ready(function () {
            $('#area_id').on('change', function() {
                var area_id = $(this).val();
                if(area_id) {
                    $.ajax({
                        url: "{{ route('get-roads-by-area') }}",
                        type: "POST",
                        data: {
                            area_id: area_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.status) {
                                var tbody = $('#sortableTable tbody');
                                tbody.empty();
                                if(response.data.length > 0) {
                                    $('#roads_container').removeClass('d-none');
                                    $.each(response.data, function(index, road) {
                                        var tr = '<tr draggable="true" data-id="' + road.id + '">' +
                                                    '<td class="serial">' + (index + 1) + '</td>' +
                                                    '<td>' + road.road_name + '</td>' +
                                                    '<td>' + (road.full_address ? road.full_address : '') + '</td>' +
                                                 '</tr>';
                                        tbody.append(tr);
                                    });
                                    updateHiddenInputs();
                                } else {
                                    $('#roads_container').addClass('d-none');
                                    $('#hidden_road_inputs').empty();
                                }
                            }
                        }
                    });
                }
            });

            let draggedRow = null;

            $("#sortableTable tbody").on("dragstart", "tr", function (e) {
                draggedRow = this;
                $(this).addClass("dragging");
                e.originalEvent.dataTransfer.effectAllowed = "move";
            });

            $("#sortableTable tbody").on("dragend", "tr", function () {
                $(this).removeClass("dragging");
                draggedRow = null;
                updateSerialNumbers();
                updateHiddenInputs();
            });

            $("#sortableTable tbody").on("dragover", "tr", function (e) {
                e.preventDefault();
                if (this === draggedRow) return;
                let tbody = $("#sortableTable tbody")[0];
                let rect = this.getBoundingClientRect();
                let middle = rect.top + (rect.height / 2);
                if (e.originalEvent.clientY < middle) {
                    tbody.insertBefore(draggedRow, this);
                } else {
                    tbody.insertBefore(draggedRow, this.nextSibling);
                }
            });

            function updateSerialNumbers() {
                $("#sortableTable tbody tr").each(function (index) {
                    $(this).find(".serial").text(index + 1);
                });
            }

            function updateHiddenInputs() {
                var container = $('#hidden_road_inputs');
                container.empty();
                $("#sortableTable tbody tr").each(function (index) {
                    var road_id = $(this).data('id');
                    container.append('<input type="hidden" name="road_items[]" value="' + road_id + '">');
                });
            }
        });
    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
