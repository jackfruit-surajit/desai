<?php
use App\Models\Role;
$user = Auth::guard('backend')->user();
$type_id = $user->type_id;
$role = Role::find($user->role_id);

?>
<li class="pc-item pc-caption">
    <label>Navigation</label>
  </li>
    
  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-dashboard')}}" class="pc-link">
      <span class="pc-micon">
        <i class="fa fa-gauge"></i>
      </span>
      <span class="pc-mtext">Dashboard</span>
    </a>
  </li>
  
  
 
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-products')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-list"></i></span><span class="pc-mtext">Product Management</span
      ></a>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-stock')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-house"></i></span><span class="pc-mtext">Stock Management</span
      ></a>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="javascript:;" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-truck"></i></span><span class="pc-mtext">Driver Management</span
      ><span class="pc-arrow"><i data-feather="chevron-right"></i></span
    ></a>
    <ul class="pc-submenu">
      <li class="pc-item"><a class="pc-link" href="{{route('driver-product-create')}}">Product Assign</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('driver-management-report')}}">Report List</a></li>
      {{-- <li class="pc-item"><a class="pc-link" href="{{route('warehouse-daily-sales-report')}}">Driver Daily Sales</a></li> --}}
      {{-- <li class="pc-item"><a class="pc-link" href="{{route('warehouse-product-daily-sales-report')}}">Product Daily Sales</a></li> --}}
    </ul>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-daily-sales-report')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-chart-line"></i></span><span class="pc-mtext">Driver Daily Sales</span
      ></a>
  </li>

  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-product-daily-sales-report')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-boxes-stacked"></i></span><span class="pc-mtext">Product Daily Sales</span
      ></a>
  </li>

  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-invoice')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-book"></i></span><span class="pc-mtext">Invoice Management</span
      ></a>
  </li>

  <li class="pc-item pc-hasmenu {{ request()->is('warehouse/vehicle*') ? 'pc-trigger active' : '' }}">
    <a href="javascript:;" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-gas-pump"></i></span><span class="pc-mtext">Fleet & Expenses</span
      ><span class="pc-arrow"><i data-feather="chevron-right"></i></span
    ></a>
    <ul class="pc-submenu">
      <li class="pc-item {{ request()->routeIs('warehouse-vehicle-fuel*') ? 'active' : '' }}"><a class="pc-link" href="{{route('warehouse-vehicle-fuel-logs')}}">Fuel Logs</a></li>
      <li class="pc-item {{ request()->routeIs('warehouse-vehicle-daily-expense*') ? 'active' : '' }}"><a class="pc-link" href="{{route('warehouse-vehicle-daily-expenses')}}">Daily Expenses</a></li>
      <li class="pc-item {{ request()->routeIs('warehouse-vehicle-repair*') ? 'active' : '' }}"><a class="pc-link" href="{{route('warehouse-vehicle-repair-logs')}}">Repair Costs</a></li>
    </ul>
  </li>






  
  
  

 



  
    




  


  

  

