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
    <a href="{{route('manufacture-dashboard')}}" class="pc-link">
      <span class="pc-micon">
        <i class="fa fa-gauge"></i>
      </span>
      <span class="pc-mtext">Dashboard</span>
    </a>
  </li>
  
  
 
  
  <li class="pc-item pc-hasmenu">
    <a href="{{route('manufacture-products')}}" class="pc-link"
      ><span class="pc-micon"> <i class="fa fa-list"></i></span><span class="pc-mtext">Product Management</span
      ></a>
  </li>
  
  <!--<li class="pc-item pc-hasmenu">-->
  <!--  <a href="{{route('roads')}}" class="pc-link"-->
  <!--    ><span class="pc-micon"> <i class="fa fa-route"></i></span><span class="pc-mtext">Route Management</span-->
  <!--    ></a>-->
  <!--</li>-->

  <!--<li class="pc-item pc-hasmenu">-->
  <!--  <a href="{{route('customer-list')}}" class="pc-link"-->
  <!--    ><span class="pc-micon"> <i class="fa fa-users"></i></span><span class="pc-mtext">Customers</span-->
  <!--    ></a>-->
  <!--</li>-->

  <!--<li class="pc-item pc-hasmenu">-->
  <!--  <a href="{{route('vehicles')}}" class="pc-link"-->
  <!--    ><span class="pc-micon"> <i class="fa fa-truck"></i></span><span class="pc-mtext">Vehicle Management</span-->
  <!--    ></a>-->
  <!--</li>-->


  
  
  

 



  
    




  


  

  

