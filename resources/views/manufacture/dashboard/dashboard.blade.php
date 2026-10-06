@extends('manufacture.layouts.main')

@section('title', 'Manufacture Dashboard')
@section('breadcrumb-item', 'Dashboard')

@section('breadcrumb-item-active', 'Dashboard')

@section('css')
<!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/plugins/jsvectormap.min.css'))}}">
@endsection

@section('content')
<?php
use App\Models\Role;
$user = Auth::guard('backend')->user();
$type_id = $user->type_id;
$role = Role::find($user->role_id);

?>
  <!-- [ Main Content ] start -->
  
  
    <div class="row">
        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <span style='font-size:22px; color:#fff;'>
                        <span style='font-size:40px;'>&#128075;</span>
                        <b style='font-size:25px; color:#fff;'>Hi </b> {{$user->name}}
                    </span>
                    <h4 class="mt-1"><i><b>{{$message}} !</b></i></h4>
                </div>
            </div>
        </div>
    </div>
    

    
  <div class="row">

    
    <div class="col-md-3 col-xxl-3">
      <div class="card statistics-card-1">
        <a href="">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-2 text-white me-3">
                <i class="fa fa-book"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Warehouse</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$warehouse}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!--<div class="col-md-3 col-xxl-3">-->
    <!--  <div class="card statistics-card-1">-->
    <!--    <a href="">-->
    <!--      <div class="card-body">-->
    <!--        <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />-->
    <!--        <div class="d-flex align-items-center">-->
    <!--          <div class="avtar bg-brand-color-2 text-white me-3">-->
    <!--            <i class="fa fa-book"></i>-->
    <!--          </div>-->
    <!--          <div>-->
    <!--            <p class="text-muted mb-0">Total Service Providers</p>-->
    <!--            <div class="d-flex align-items-end">-->
    <!--              <h2 class="mb-0 f-w-500">10</h2>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </a>-->
    <!--  </div>-->
    <!--</div>-->

    <!--<div class="col-md-3 col-xxl-3">-->
    <!--  <div class="card statistics-card-1">-->
    <!--    <a href="">-->
    <!--      <div class="card-body">-->
    <!--        <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />-->
    <!--        <div class="d-flex align-items-center">-->
    <!--          <div class="avtar bg-brand-color-2 text-white me-3">-->
    <!--            <i class="fa fa-book"></i>-->
    <!--          </div>-->
    <!--          <div>-->
    <!--            <p class="text-muted mb-0">Total Bookings</p>-->
    <!--            <div class="d-flex align-items-end">-->
    <!--              <h2 class="mb-0 f-w-500">10</h2>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </a>-->
    <!--  </div>-->
    <!--</div>-->
 

    
    


    
  </div>
 
  <!-- [ Main Content ] end -->
@endsection
