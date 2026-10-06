<?php
    use Illuminate\Support\Facades\DB as DB;
    $favicon = DB::table('settings')->where('slug','=','site_favicon')->first();
    $site_title = DB::table('settings')->where('slug','=','site_title')->first();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{isset($site_title->value) ? $site_title->value : env('APP_NAME', '') }} | Admin Login</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <script type="text/javascript">
        var full_path = '<?= url('/') . '/admin/'; ?>';
    </script>

    <!-- [Favicon] icon -->
    <!--<link rel="icon" href="{{ isset($favicon->value) ? URL::asset(asset_path('uploads/setting/'.$favicon->value)) :  ''}}" type="image/x-icon">-->
    <link rel="icon" type="image/x-icon" href="{{ URL::asset(asset_path('assets_front/icon/desai-fav.jpg'))}}" type="image/x-icon">
    @yield('css')

    @include('admin.layouts.head-css')
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr"
    data-pc-theme="light">

    @include('admin.layouts.loader')

    @if (View::hasSection('auth-v2'))
        <div class="auth-main v2">
            <div class="bg-overlay bg-dark"></div>
            <div class="auth-wrapper">
                <div class="auth-sidecontent">
                    @include('admin.layouts.authFooter')
                </div>
            @else
                <div class="auth-main v1">
                    <div class="auth-wrapper">
    @endif
    @yield('content')
    @if (!View::hasSection('auth-v2'))
        @include('admin.layouts.authFooter')
    @endif
    </div>
    </div>
    @if (View::hasSection('auth-v2'))
        </div>
    @endif
    @include('admin.layouts.customizer')

    @include('admin.layouts.footerjs')

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
            toastr.error(error_msg, '')
        </script>
    @endif

</body>

</html>
