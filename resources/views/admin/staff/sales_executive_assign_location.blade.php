@extends('admin.layouts.main')

@section('title', 'Staff Management')
@section('breadcrumb-item', 'Staff Management')

@section('breadcrumb-item-active', 'Assigned Area')

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

.drag-handle {
    text-align: center !important;
    cursor: grab !important;
    font-size: 22px;
}

.drag-handle:active {
    cursor: grabbing !important;
}

.save-btn {
    margin-top: 20px;
    padding: 10px 25px;
    background: #007bff;
    color: white;
    border: 0;
    border-radius: 5px;
    cursor: pointer;
}

.save-btn:hover {
    background: #0056b3;
} 
    
</style>


@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">

        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                <h5>Assign areas of {{$model->name}}</h5>
                </div>
                <div class="card-body">

                    <form class="row g-3" method="post" action="{{route('sales-executive-area-update')}}" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="user_id" id="user_id" value="{{$model->id}}">
                        <div class="col-md-12">
                            <label for="" class="form-label">Area<span class="required">*</span> (Maximum 5 area)</label>
                            @php
                                $selectedIds = [];
                                if (isset($selected_areas)) {
                                    if (is_array($selected_areas)) {
                                        $selectedIds = $selected_areas;
                                    } elseif ($selected_areas instanceof \Illuminate\Support\Collection) {
                                        $selectedIds = $selected_areas->pluck('area_id')->toArray();
                                    }
                                }
                                $savedOrder = old('area_order') ? explode(',', old('area_order')) : (old('area_id') ?? $selectedIds);
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
                        
                        <div class="col-md-12 text-end btn-page">
                            <a href="{{route('sales-executives')}}" class="btn btn-outline-secondary">Back</a>
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
    
        $(document).ready(function () {

            let draggedRow = null;

            // Drag start
            $("#sortableTable tbody").on("dragstart", "tr", function (e) {
        
                draggedRow = this;
        
                $(this).addClass("dragging");
        
                e.originalEvent.dataTransfer.effectAllowed = "move";
            });


    // Drag end
            $("#sortableTable tbody").on("dragend", "tr", function () {
        
                $(this).removeClass("dragging");
        
                draggedRow = null;
        
                updateSerialNumbers();
            });
        
        
            // Drag over
            $("#sortableTable tbody").on("dragover", "tr", function (e) {
        
                e.preventDefault();
        
                if (this === draggedRow) {
                    return;
                }
        
                let tbody = $("#sortableTable tbody")[0];
        
                let rect = this.getBoundingClientRect();
        
                let middle = rect.top + (rect.height / 2);
        
                if (e.originalEvent.clientY < middle) {
        
                    tbody.insertBefore(draggedRow, this);
        
                } else {
        
                    tbody.insertBefore(
                        draggedRow,
                        this.nextSibling
                    );
        
                }
        
            });
        
        
            // Update serial numbers
            function updateSerialNumbers() {
        
                $("#sortableTable tbody tr").each(function (index) {
        
                    $(this)
                        .find(".serial")
                        .text(index + 1);
        
                });
        
            }
        
        
            // Save order
            $("#saveOrder").click(function () {
                
                let user_id = $("#user_id").val();
        
                let items = [];
        
                $("#sortableTable tbody tr").each(function (index) {
        
                    items.push({
                        id: $(this).data("id"),
                        sort_order: index + 1
                    });
        
                });
        
        
                $.ajax({
        
                   
                    url: "{{ route('driver-road-arrangement') }}",
                    type: "POST",
        
                    data: {
                        items: items, user_id: user_id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
        
                    beforeSend: function () {
        
                        $("#saveOrder")
                            .prop("disabled", true)
                            .text("Saving...");
        
                    },
        
                    success: function (response) {
        
                        if (response.status) {
        
                            alert(response.message);
        
                        }
        
                    },
        
                    error: function (xhr) {
        
                        console.log(xhr.responseText);
        
                        alert("Unable to update order.");
        
                    },
        
                    complete: function () {
        
                        $("#saveOrder")
                            .prop("disabled", false)
                            .text("Save Order");
        
                    }
        
                });
        
            });
        
        });
    
    </script>
    
    <!-- [Page Specific JS] end -->
@endsection
