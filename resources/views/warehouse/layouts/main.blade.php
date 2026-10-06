<?php
    use Illuminate\Support\Facades\DB as DB;
    $favicon = DB::table('settings')->where('slug','=','site_favicon')->first();
    $site_title = DB::table('settings')->where('slug','=','site_title')->first();

    $site_logo_white = DB::table('settings')->where('slug','=','site_logo')->first();
    $site_logo_dark = DB::table('settings')->where('slug','=','site_logo_dark')->first();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{isset($site_title->value) ? $site_title->value : env('APP_NAME', '') }} | @yield('title')</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content=""/>

    <meta name="author" content="" />

    {{-- <script type="text/javascript">
        var full_path = '<?= url('/') . '/admin/'; ?>';
        var logo_white = '<?= url('/') . '/uploads/setting/'.$site_logo_white->value; ?>';
        var logo_dark = '<?= url('/') . '/uploads/setting/'.$site_logo_dark->value; ?>';
    </script> --}}

    <meta name="csrf-token" content="{{csrf_token()}}">

    <!-- [Favicon] icon -->
    <!--<link rel="icon" href="{{ isset($favicon->value) ? URL::asset(asset_path('uploads/setting/'.$favicon->value)) :  ''}}" type="image/x-icon">-->

    <link rel="icon" type="image/x-icon" href="{{ URL::asset(asset_path('assets_front/icon/desai-delivery.png'))}}">
    @yield('css')

    @include('warehouse.layouts.head-css')
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light" >
    @include('warehouse.layouts.loader')
    @include('warehouse.layouts.sidebar')
    @include('warehouse.layouts.topbar')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            @if(View::hasSection('breadcrumb-item'))
                @include('warehouse.layouts.breadcrumb')
            @endif
            <!-- [ Main Content ] start -->
            @yield('content')
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

    @include('warehouse.layouts.footer')
    @include('warehouse.layouts.customizer')

    @include('warehouse.layouts.footerjs')

    @yield('scripts')

    @if(Session::has('success_msg'))
        <input type="hidden" id="success_msg" value="{{ Session::get('success_msg') }}"/>
        <script>
            var success_msg = $('#success_msg').val();
            toastr.success(success_msg, '');
        </script>
    @endif

    @if(Session::has('error_msg'))
        <input type="hidden" id="error_msg" value="{{ Session::get('error_msg') }}"/>
        <script>
            var error_msg = $('#error_msg').val();
            toastr.error(error_msg, '');
        </script>
    @endif

  </body>
  <!-- [Body] end -->
</html>