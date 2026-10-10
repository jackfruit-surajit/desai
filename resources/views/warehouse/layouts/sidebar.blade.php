<style>
    :root{
        --desai-navy-deep: #101C4C;
        --desai-navy-mid: #1B2255;
        --desai-navy-light: #35429E;
        --desai-amber-light: #EE1A28;
        --desai-amber-deep: #EE1A28;
    }

    /* ===== Sidebar / Header / Footer / Container base layers ===== */
    .pc-sidebar{
        /*background: linear-gradient(180deg, var(--desai-navy-deep) 0%, var(--desai-navy-mid) 100%);*/
        background: linear-gradient(0deg, var(--desai-navy-deep) 35%, #EE1A28 100%);
         /*background-color: var(--desai-navy-deep);*/
         /*background-color: #E51A29;*/
        background-position: center;
        box-shadow: 0 2px 10px rgb(255, 255, 255);
     }
    .pc-sidebar .m-header{ background-color: transparent; }
    .simplebar-wrapper{ background-color: transparent; }

    .pc-sidebar .b-brand{
        /*background: rgba(255, 255, 255, 0.34);*/
        background-color: #fff;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
    }
    .pc-sidebar ul {
        padding: 0 0 15% 0;
    }
    form .btn-success {
        margin: 8px 0 -10px 0;
    }

    .pc-header{
       /*background: linear-gradient(140deg, var(--desai-navy-deep) 0%, #EE1A28  140%);*/
       /*background: linear-gradient(180deg, var(--desai-navy-deep) 0%, var(--desai-navy-mid) 100%);*/
       background-color: #EE1A28;
       box-shadow: 0 2px 10px rgb(255, 255, 255);
       /*border-bottom: 1px solid #fff;*/
    }
    .pc-footer{
        padding: 15px 0;
        /*background: linear-gradient(140deg, var(--desai-navy-deep) 0%, #EE1A28  140%);*/
       box-shadow: 0 2px 10px rgb(255, 255, 255);
       background-color: var(--desai-navy-deep);
        color: #fff;
    }

    .pc-container {
          /*background: url(../public/backend/assets/images/desain-admin-banner.png) no-repeat;*/
          /*background: linear-gradient(180deg, var(--desai-navy-deep) 0%, var(--desai-navy-mid) 100%);*/
          background: linear-gradient(0deg, var(--desai-navy-deep) 35%, #EE1A28  100%);
        background-size: cover;
        background-position: center;
    }
    /*.pc-container .pc-content {*/
    /*    background: url(../public/backend/assets/images/desain-admin-banner.png) no-repeat;*/
    /*    background-size: cover;*/
    /*    background-position: center;*/
    /*}*/

    /* ===== Text hierarchy ===== */
    .page-header-title h2{ color: #fff; }
    .page-header .breadcrumb a{ color: rgba(255,255,255,0.65); }
    .page-header .breadcrumb .breadcrumb-item:last-child{ color: #fff; }

    /* ===== Base nav link ===== */
    .pc-sidebar .pc-link{
        color: rgba(255,255,255,0.75);
        border-radius: 8px;
        border: 1px solid #ffffff70;
        margin: 0 0 8px 0;
    }
    .pc-sidebar .pc-link:hover{
        background: rgba(255,255,255,0.06);
        color: #fff;
    }

    /* ===== Active row — solid warm gradient wash, not a muddy low-opacity blend ===== */
    .pc-sidebar .pc-navbar>.pc-item.active>.pc-link:after{
        /*background: linear-gradient(90deg, rgb(16, 28, 76), rgba(16, 28, 76, 0.48));*/
        /*background: linear-gradient(0deg, var(--desai-navy-deep) 35%, #EE1A28 100%);*/
        border-left: 3px solid #171D4B;
        border-radius: 6px;
        opacity: 1;
    }
    .pc-sidebar .pc-navbar>.pc-item.active>.pc-link{
        font-weight: 500;
          color: #101c4c !important;
          border: none;
          padding: 18px 25px;
          border-radius: 25px;
    }
    .pc-sidebar .pc-navbar>.pc-item:hover:not(.active)>.pc-link:after{
        background: rgba(255,255,255,0.06);
    }

    .pc-sidebar .pc-navbar>.pc-item .pc-submenu .pc-item.pc-trigger>.pc-link,
    .pc-sidebar .pc-navbar>.pc-item .pc-submenu .pc-item.active>.pc-link{
        border-radius: 6px;
        color: #eee;
        background-color: #192153d4;
    }
    .pc-sidebar .pc-navbar>.pc-item .pc-submenu .pc-item.active>.pc-link:hover {
        color: #000;
    }
    /* ===== Icon chips ===== */
    .pc-sidebar .pc-micon{
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: rgba(255,255,255,0.05);
        margin-right: 12px;
    }
    /* Active icon chip: solid amber pill, not a translucent wash */
    .pc-sidebar .pc-item.active .pc-micon{
        /*background: linear-gradient(180deg, var(--desai-amber-light), var(--desai-amber-deep));*/
        background: linear-gradient(180deg, #FFF, #FFF);
    }
    .pc-sidebar .pc-item.active .pc-micon i{
        color: var(--desai-navy-deep);
    }

    /* ===== User card ===== */
    .card {
        background-color: #cacbd01c  !important;
        border-color: #eee; !important;
    }
    .card .card-body .text-muted {
        color: #fff !important;
    }
    .card .card-body h2 {
        color: #f7b12e !important;
    }
    .statistics-card-1 .card-body .img-bg {
        display: none;
    }
    .table th {
        color: #fff;
    }
    .table td {
        color: #eee;
    }
    h1, h2, h3, h4 {
        color: #fff;
    }
    #staff-management_info {
        color: #fff !important;
    }
    #staff-management_wrapper{
        color: #eee;
    }
    
    .card .card-header h5, .card .card-header .h5 {
        color: #fff;
    }
    .card-body .form-label {
        color: #fff;
    }
    .pc-sidebar .card.pc-user-card{
        background: rgba(255,255,255,0.05);
        /*background-color: #192153;*/
        border-top: 1px solid rgba(255,255,255,0.08);
        margin: -70px 0 0;
    }
     .pc-sidebar .btm-s .card-body{
        background-color: #192153;
    }
    .pc-sidebar .card.pc-user-card small {
        color: #eee !important;
    }
    .pc-sidebar .card.pc-user-card .btn-icon {
        color: #fff;
    }
    .pc-sidebar .card.pc-user-card .card-body h6{ color: #fff; }
    .pc-sidebar .card.pc-user-card .dropdown h6{ color: #fff; }

    /* ===== Buttons ===== */
    .card .card-header .btn{
        background: linear-gradient(180deg, var(--desai-amber-light), var(--desai-amber-deep));
        border-radius: 8px;
        border-color: var(--desai-amber-deep);
        color: #fff;
        font-weight: 600;
    }
    .card .card-header .btn:hover{
        background: var(--desai-navy-deep);
        border-color: var(--desai-navy-deep);
        color: #fff;
    }
    .btn-page .btn-outline-secondary {
        color: #fff;
        border-color: #fff;
    }
    .btn-primary{
        background: linear-gradient(180deg, var(--desai-amber-light), var(--desai-amber-deep));
          border-radius: 8px;
          border-color: var(--desai-amber-deep);
          color: #fff;
          font-weight: 600;
    }
    
    /* Remove the vertical line on the left of submenu */
        .pc-sidebar .pc-navbar .pc-submenu {
            border-left: none !important;
        }
        
        .pc-sidebar .pc-navbar .pc-submenu::before {
            display: none !important;
        }
    .pc-sidebar .pc-navbar .pc-submenu .pc-item::before {
    display: none !important;
}

/* Remove the vertical connector line in submenu */
/* Kill the submenu vertical guide line */
.pc-sidebar .pc-navbar .pc-item > .pc-submenu,
.pc-sidebar .pc-navbar .pc-submenu {
    border-left: 0 !important;
    border-inline-start: 0 !important;
    margin-left: 0 !important;
    padding-left: 0 !important;
}

.pc-sidebar .pc-navbar .pc-submenu::before,
.pc-sidebar .pc-navbar .pc-submenu::after,
.pc-sidebar .pc-navbar .pc-submenu .pc-item::before,
.pc-sidebar .pc-navbar .pc-submenu .pc-item::after {
    display: none !important;
    content: none !important;
}
    /* ===== Table headers ===== */
    .datatable-table.dataTable[class*=table-] thead th,
    .table.dataTable[class*=table-] thead th{
       background: linear-gradient(180deg, var(--desai-navy-deep), var(--desai-navy-light));
       color: #fff;
    }
</style>

<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header" style="justify-content: center;">
            <a href="{{route('warehouse-dashboard')}}" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="{{ URL::asset(asset_path('assets_front/icon/desai-delivery.png')) }}" alt="logo-image" style="">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                @include('warehouse.layouts.menu-list')
            </ul>
            
        </div>
        <div class="card pc-user-card btm-s">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{isset(Auth()->guard('backend')->user()->image) ? URL::asset(asset_path('uploads/staff/'.Auth()->guard('backend')->user()->image )) : URL::asset(asset_path('uploads/user/no-img.png'))}}" alt="user-image"
                            class="user-avtar wid-45 rounded-circle">
                    </div>
                    
                

                        <div class="flex-grow-1 ms-3">
                        <div class="dropdown">
                          <a href="#" class="arrow-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,20">
                            <div class="d-flex align-items-center">
                              <div class="flex-grow-1 me-2">
                                <h6 class="mb-0">{{Auth()->guard('backend')->user()->name}}</h6>
                                <small>Warehouse </small>
                              </div>
                              <div class="flex-shrink-0">
                                <div class="btn btn-icon btn-link-secondary avtar">
                                  <i class="ph-duotone ph-windows-logo"></i>
                                </div>
                              </div>
                            </div>
                          </a>
                        
                    </div>
            
                
                
            </div>
        </div>
    </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
