<?php
    use Illuminate\Support\Facades\DB as DB;
    $site_title = DB::table('settings')->where('slug','=','site_title')->first();
?>
<footer class="pc-footer">
  <div class="footer-wrapper container-fluid">
    <div class="row">
      <div class="col-sm-6 my-1">
        
      </div>
      <div class="col-sm-6 ms-auto my-1">
        <ul class="list-inline footer-link mb-0 justify-content-sm-end d-flex">
          <li class="list-inline-item"><p class="m-0">Copyright &copy; {{date('Y')}} <strong><span> {{isset($site_title->value) ? $site_title->value : env('APP_NAME', '') }} </span></strong>. All Rights Reserved.</p></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
