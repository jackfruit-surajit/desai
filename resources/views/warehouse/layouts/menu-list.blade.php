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
    </ul>
  </li>
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('warehouse-invoice')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-book"></i></span><span class="pc-mtext">Invoice Management</span
      ></a>
  </li>






  
  
  

 



  
    




  


  

  

