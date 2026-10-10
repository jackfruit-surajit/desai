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
    <a href="{{route('admin-dashboard')}}" class="pc-link">
      <span class="pc-micon">
        <i class="fa fa-gauge"></i>
      </span>
      <span class="pc-mtext">Dashboard</span>
    </a>
  </li>
  
  
  <li class="pc-item pc-hasmenu">
    <a href="javascript:;" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-user"></i></span><span class="pc-mtext">Staff Management</span
      ><span class="pc-arrow"><i data-feather="chevron-right"></i></span
    ></a>
    <ul class="pc-submenu">
      <li class="pc-item"><a class="pc-link" href="{{route('drivers')}}">Drivers</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('sales-executives')}}">Sales Executives</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('warehouses')}}">Warehouse</a></li>
    </ul>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('areas')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-list"></i></span><span class="pc-mtext">Area Management</span
      ></a>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('roads')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-route"></i></span><span class="pc-mtext">Route Management</span
      ></a>
  </li>

  <li class="pc-item pc-hasmenu">
    <a href="{{route('customer-list')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-users"></i></span><span class="pc-mtext">Customers / Shops</span
      ></a>
  </li>

  <li class="pc-item pc-hasmenu">
    <a href="{{route('vehicles')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-truck"></i></span><span class="pc-mtext">Vehicle Management</span
      ></a>
  </li>


  <!--<li class="pc-item pc-hasmenu">-->
  <!--  <a href="#" class="pc-link"-->
  <!--    ><span class="pc-micon"> <i class="fa fa-house"></i></span><span class="pc-mtext">Warehouse Management</span-->
  <!--    ></a>-->
  <!--</li>-->
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('products')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-list"></i></span><span class="pc-mtext">Product Management</span
      ></a>
  </li>
  
  
  <li class="pc-item pc-hasmenu">
    <a href="javascript:;" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-book"></i></span><span class="pc-mtext">MIS & Reports</span
      ><span class="pc-arrow"><i data-feather="chevron-right"></i></span
    ></a>
    <ul class="pc-submenu">
    
      <li class="pc-item"><a class="pc-link" href="{{route('invoice-list')}}">Daily sales</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('driver-attendance')}}">Driver Attendance</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('driver-route-logs')}}">Driver Route Logs</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('sales-executive-attendance')}}">Sales Executive Attendance</a></li>
      <li class="pc-item"><a class="pc-link" href="{{route('sales-executive-area-logs-list')}}">Sales Executive Area Logs</a></li>

    </ul>
  </li>
  
  

 



  
    




  


  

  

