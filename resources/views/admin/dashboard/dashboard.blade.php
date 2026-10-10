@extends('admin.layouts.main')

@section('title', 'Admin Dashboard')
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
    
    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('customer-list') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-1 text-white me-3">
                <i class="fa fa-users"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Customers</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$customer ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('sales-executives') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-2 text-white me-3">
                <i class="fa fa-user-tie"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Sales Executives</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$sales_executive ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('products') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-3 text-white me-3">
                <i class="fa fa-box"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Products</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$product ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('areas') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-4 text-white me-3">
                <i class="fa fa-map-marker-alt"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Areas</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$area ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('roads') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-5 text-white me-3">
                <i class="fa fa-route"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Routes</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$route ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4 col-xxl-4">
      <div class="card statistics-card-1">
        <a href="{{ route('vehicles') }}">
          <div class="card-body">
            <img src="{{ URL::asset(asset_path('backend/assets/images/widget/img-status-7.svg'))}}" alt="img" class="img-fluid img-bg" />
            <div class="d-flex align-items-center">
              <div class="avtar bg-brand-color-6 text-white me-3">
                <i class="fa fa-truck"></i>
              </div>
              <div>
                <p class="text-muted mb-0">Total Vehicles</p>
                <div class="d-flex align-items-end">
                  <h2 class="mb-0 f-w-500">{{$vehicle ?? 0}}</h2>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

  </div>
 
  <!-- [ Main Content ] end -->
@endsection
