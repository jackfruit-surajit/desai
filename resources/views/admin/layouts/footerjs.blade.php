<!-- Required Js -->
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery.min.js'))}}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/popper.min.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/simplebar.min.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/bootstrap.min.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/fonts/custom-font.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/pcoded.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/feather.min.js')) }}"></script>

<script src="{{ URL::asset(asset_path('backend/assets/js/toastr.min.js')) }}"></script>

<!--<script src="{{ URL::asset(asset_path('backend/assets/js/ckeditor/ckeditor.js'))}}"></script>-->

<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/dataTables.min.js')) }}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/plugins/dataTables.bootstrap5.min.js')) }}"></script>

<script src="{{ URL::asset(asset_path('backend/assets/js/select/select2.min.js'))}}"></script>


<!-- <script src="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.js'))}}"></script>
<script src="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.filter.js'))}}"></script> -->

<!-- <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

<script src="{{ URL::asset(asset_path('backend/assets/custom/js/backend.js'))}}"></script>

@if(Session::has('layout'))
    <input type="hidden" id="layout" value="{{ Session::get('layout') }}"/>
    <script>
        
        $(document).ready(function () {
            var layout = $('#layout').val();
            layout_change(layout);
        });
        
    </script>
@endif

@if (env('APP_DARK_LAYOUT') == 'default')
<script>
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        dark_layout = 'true';
    } else {
        dark_layout = 'false';
    }
    layout_change_default();
    if (dark_layout == 'true') {
        layout_change('dark');
    } else {
        layout_change('light');
    }
</script>
@endif

@if (env('APP_DARK_LAYOUT') != 'default')
    @if (env('APP_DARK_LAYOUT') == 'true')
        <script>
            alert(2);
            layout_change('dark');
        </script>
    @endif
    @if (env('APP_DARK_LAYOUT') == 'false')
        <script>
            alert(1);
            layout_change('light');
        </script>
    @endif
@endif


@if (env('APP_DARK_NAVBAR') == 'true')
    <script>
        layout_sidebar_change('dark');
    </script>
@endif

@if (env('APP_DARK_NAVBAR') == false)
    <script>
        layout_sidebar_change('light');
    </script>
@endif

@if (env('APP_BOX_CONTAINER') == false)
    <script>
        change_box_container('true');
    </script>
@endif

@if (env('APP_BOX_CONTAINER') == false)
    <script>
        change_box_container('false');
    </script>
@endif

@if (env('APP_CAPTION_SHOW') == 'true')
    <script>
        layout_caption_change('true');
    </script>
@endif

@if (env('APP_CAPTION_SHOW') == false)
    <script>
        layout_caption_change('false');
    </script>
@endif

@if (env('APP_RTL_LAYOUT') == 'true')
    <script>
        layout_rtl_change('true');
    </script>
@endif

@if (env('APP_RTL_LAYOUT') == false)
    <script>
        layout_rtl_change('false');
    </script>
@endif

@if (env('APP_PRESET_THEME') != '')
    <script>
        preset_change("{{env('APP_PRESET_THEME')}}");
    </script>
@endif
