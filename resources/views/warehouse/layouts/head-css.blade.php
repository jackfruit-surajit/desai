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
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" >

    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/css/plugins/dataTables.bootstrap5.min.css')) }}">

    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/select/select2.min.css')) }}" >

    <!-- <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.css')) }}" >
    <link rel="stylesheet" href="{{ URL::asset(asset_path('backend/assets/js/multi-select/jquery.multiselect.filter.css')) }}" > -->

    <!-- <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">

    <style>
        /* Card Headers & Titles in White */
        .card-header,
        .card-header h1,
        .card-header h2,
        .card-header h3,
        .card-header h4,
        .card-header h5,
        .card-header h6,
        .card-header .card-title,
        .card-title,
        .card .card-title,
        .card-header span,
        .card h4,
        .card h5 {
            color: #ffffff !important;
        }

        /* DataTables Controls: entries per page, Search, Showing info, empty, paginate in White */
        .dataTables_wrapper,
        .dataTables_wrapper label,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_paginate .paginate_button,
        .dataTables_wrapper .dataTables_empty,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_length,
        .dataTables_length label,
        .dataTables_filter,
        .dataTables_filter label,
        .dataTables_info,
        .dataTables_empty,
        .dataTables_processing {
            color: #ffffff !important;
        }

        /* DataTables Search Input & Length Select */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_filter input {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 4px;
            padding: 4px 10px;
        }
        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_filter input:focus {
            background-color: rgba(255, 255, 255, 0.18) !important;
            border-color: #35429E !important;
            color: #ffffff !important;
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter input::placeholder,
        .dataTables_filter input::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_length select {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 4px;
            padding: 4px 8px;
        }
        .dataTables_wrapper .dataTables_length select option,
        .dataTables_length select option {
            background-color: #101C4C !important;
            color: #ffffff !important;
        }

        /* DataTables Pagination in White */
        .dataTables_wrapper .dataTables_paginate .paginate_button,
        .dataTables_paginate .paginate_button {
            color: #ffffff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_paginate .paginate_button.disabled {
            color: rgba(255, 255, 255, 0.4) !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link,
        .dataTables_paginate .paginate_button.current {
            background-color: #35429E !important;
            border-color: #35429E !important;
            color: #ffffff !important;
        }
    </style>