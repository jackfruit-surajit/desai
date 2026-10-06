<?php
    use Illuminate\Support\Facades\DB as DB;
    $site_title = DB::table('settings')->where('slug','=','site_title')->first();
    $site_logo_dark = DB::table('settings')->where('slug','=','site_logo_dark')->first();
?>

<div class="auth-sidefooter">
    
    <hr class="mb-3 mt-4" />
    <div class="row">
      <div class="col my-1">
      <img src="{{ URL::asset(asset_path('assets_front/icon/keshav_logo.png')) }}" height="50px" class="" alt="images" />
      </div>
      <div class="col-auto my-1">
        <ul class="list-inline footer-link mb-0 justify-content-sm-end d-flex">
          <li class="list-inline-item"><p class="m-0">Copyright &copy; {{date('Y')}} <strong><span> {{isset($site_title->value) ? $site_title->value : env('APP_NAME', '') }} </span></strong>. All Rights Reserved.</p></li>
        </ul>
      </div>
    </div>
  </div>
  