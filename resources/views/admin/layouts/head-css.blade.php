    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/fonts/tabler-icons.min.css')) }}" >
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/fonts/feather.css')) }}" >
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/fonts/fontawesome.css')) }}" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/fonts/material.css')) }}" >
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/style.css')) }}" id="main-style-link" >
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/style-preset.css')) }}" >

    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/toastr.min.css')) }}" >

    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/plugins/dataTables.bootstrap5.min.css')) }}">

    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/select/select2.min.css')) }}" >

    <!-- <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.css')) }}" >
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.filter.css')) }}" > -->

    <!-- <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">

    <style>
        /* Premium UI Enhancements */
        .card {
            border: none;
            box-shadow: 0 4px 20px 0 rgba(0,0,0,0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 25px 0 rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .pc-header {
            background: linear-gradient(135deg, #0a192f 0%, #172a45 100%) !important;
            box-shadow: 0 4px 15px rgba(10, 25, 47, 0.4) !important;
        }
        .pc-header .pc-head-link {
            background: rgba(255, 255, 255, 0.1) !important;
        }
        .pc-header .pc-head-link i {
            color: #ffffff !important;
        }
        .pc-header .user-avtar {
            border: 2px solid #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--bs-primary), #6133ff);
            border: none;
            box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #6133ff, var(--bs-primary));
            box-shadow: 0 6px 15px rgba(var(--bs-primary-rgb), 0.4);
            transform: translateY(-1px);
        }
        .table thead th {
            background-color: #f8f9fa !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.03) !important;
        }
        .pc-sidebar .pc-item.active > .pc-link {
            background: linear-gradient(90deg, rgba(var(--bs-primary-rgb), 0.1), transparent);
            border-left: 3px solid var(--bs-primary);
        }

        /* Global Table Responsiveness */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            display: block;
        }
        .table {
            white-space: nowrap;
            width: 100% !important;
            max-width: 100%;
        }
        .table td, .table th {
            vertical-align: middle;
        }

        /* Mobile Responsiveness Improvements */
        @media (max-width: 767.98px) {
            .card {
                margin-bottom: 15px;
            }
            .card-header, .card-body {
                padding: 15px;
            }
            .table-responsive {
                border: 0;
            }
            .action-btns {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
            .action-btns .badge, .action-btns a {
                margin: 0 !important;
                font-size: 11px;
                padding: 6px 10px;
                display: inline-block;
            }
            .pc-content {
                padding: 15px 10px !important;
            }
            h4, .h4 {
                font-size: 1.1rem;
            }
            .card-header .row > div {
                margin-bottom: 10px;
            }
            .card-header .text-end, .card-header .float-end {
                text-align: left !important;
                float: none !important;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .d-inline-flex {
                flex-wrap: wrap;
                gap: 10px;
            }
            .form-select, .form-control {
                width: 100% !important;
                max-width: none !important;
            }
            .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_length {
                text-align: left !important;
                margin-bottom: 10px;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-left: 0;
                margin-top: 5px;
            }
            .btn {
                width: auto;
            }
            .pc-header .pc-head-link {
                padding: 0.5rem;
            }
        }
    </style>