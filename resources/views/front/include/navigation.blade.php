  <!-- ════════════════  MOBILE OVERLAY (dark backdrop) ═══════════════ -->
    <div class="saki-mobile-overlay" id="sakiMobileOverlay"></div>

    <!-- ═════════════════  MOBILE DRAWER (slide-in from right) ════════════════ -->
    <div class="saki-mobile-drawer" id="sakiMobileDrawer">

        <!-- Drawer Header -->
        <div class="saki-drawer-header">
            <img src="{{URL::asset(asset_path('assets_front/icon/saki-logo.png'))}}" alt="Saki Services">
            <button class="saki-drawer-close" id="sakiDrawerClose">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Scroll wrapper: handles overflow-y scroll WITHOUT clipping dropdown -->
        <div class="saki-drawer-scroll-wrap">

        <!-- Drawer Nav Links -->
        <nav class="saki-drawer-nav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('/')}}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="drawerServicesToggle">Services</a>
                    <ul class="dropdown-menu" id="drawerServicesMenu">
                        <li><a class="dropdown-item" href="#">Plumbing</a></li>
                        <li><a class="dropdown-item" href="#">Electrical</a></li>
                        <li><a class="dropdown-item" href="#">AC Repair</a></li>
                        <li><a class="dropdown-item" href="#">Cleaning</a></li>
                        <li><a class="dropdown-item" href="#">Carpentry</a></li>
                        <li><a class="dropdown-item" href="#">Painting</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>
            </ul>
        </nav>

        <!-- Drawer Auth Section (Login / Register) -->
        <div class="saki-drawer-auth">
            <div class="saki-drawer-auth-title">Account</div>
            <a href="login.php">
                <i class="fa-solid fa-right-to-bracket"></i>
                Login
            </a>
            <a href="register.php">
                <i class="fa-solid fa-user-plus"></i>
                Register
            </a>
        </div>

        </div><!-- end .saki-drawer-scroll-wrap -->

    </div>


    <!-- ═════════════════  NAVBAR ════════════════════ -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <img src="{{URL::asset(asset_path('assets_front/icon/saki-logo.png'))}}" alt="Saki Services">
            </a>
              <!-- ── DESKTOP: Nav links (hidden on mobile via CSS) ── -->
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ml-auto gap-1">
                    <li class="nav-item"><a class="nav-link active" href="{{route('/')}}">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Services</a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item" href="{{route('service')}}">Plumbing</a></li>
                            <li><a class="dropdown-item" href="{{route('service')}}">Electrical</a></li>
                            <li><a class="dropdown-item" href="{{route('service')}}">AC Repair</a></li>
                            <li><a class="dropdown-item" href="{{route('service')}}">Cleaning</a></li>
                            <li><a class="dropdown-item" href="{{route('service')}}">Carpentry</a></li>
                            <li><a class="dropdown-item" href="{{route('service')}}">Painting</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                </ul>
            </div>


            <!-- ── DESKTOP: Search bar (hidden on mobile via CSS) ── -->
            <div class="saki-navbar-search">
                <div class="saki-search-field-wrap">
                    <i class="fa-solid fa-magnifying-glass saki-search-inside-icon"></i>
                    <input type="text"
                        id="sakiSearchInput"
                        class="form-control saki-service-search-input"
                        placeholder="Search services..."
                        autocomplete="off">
                    <button class="saki-clear-btn" id="sakiClearBtn" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="saki-search-dropdown" id="sakiSearchDropdown">
                        <a href="#" class="saki-search-item">
                            <i class="fa-solid fa-snowflake fa-c"></i> AC Repair
                        </a>
                        <a href="#" class="saki-search-item">
                            <i class="fa-solid fa-broom fa-c"></i> Home Cleaning
                        </a>
                        <a href="#" class="saki-search-item">
                            <i class="fa-solid fa-bolt fa-c"></i> Electrician
                        </a>
                        <a href="#" class="saki-search-item">
                            <i class="fa-solid fa-faucet-drip fa-c"></i> Plumbing
                        </a>
                    </div>
                </div>
            </div>

          
            <!-- ── DESKTOP: User dropdown (hidden on mobile via CSS) ── -->
            <div class="saki-user-dropdown">
                <button class="saki-user-btn" id="sakiUserBtn">
                    <i class="fa-regular fa-user"></i>
                </button>
                <div class="saki-user-menu" id="sakiUserMenu">
                    <a href="{{route('login')}}">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                    <a href="{{route('register')}}">
                        <i class="fa-solid fa-user-plus"></i> Register
                    </a>
                </div>
            </div>

            <!-- ── MOBILE ONLY: Search icon + Hamburger ── -->
            <div class="saki-mobile-icons">
                <!-- Search toggle icon -->
                <button class="saki-mobile-search-btn" id="sakiMobileSearchBtn" type="button" aria-label="Toggle search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <!-- Hamburger (opens drawer) -->
                <button class="navbar-toggler border-0" id="sakiHamburger" type="button" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </div>

        <!-- ── MOBILE ONLY: Collapsible search bar (below navbar) ── -->
        <div class="saki-mobile-search-bar" id="sakiMobileSearchBar">
            <div class="saki-search-field-wrap">
                <i class="fa-solid fa-magnifying-glass saki-search-inside-icon"></i>
                <input type="text"
                    id="sakiMobileSearchInput"
                    class="form-control saki-service-search-input"
                    placeholder="Search services..."
                    autocomplete="off">
                <button class="saki-clear-btn" id="sakiMobileClearBtn" type="button">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="saki-search-dropdown" id="sakiMobileSearchDropdown">
                    <a href="#" class="saki-search-item">
                        <i class="fa-solid fa-snowflake fa-c"></i> AC Repair
                    </a>
                    <a href="#" class="saki-search-item">
                        <i class="fa-solid fa-broom fa-c"></i> Home Cleaning
                    </a>
                    <a href="#" class="saki-search-item">
                        <i class="fa-solid fa-bolt fa-c"></i> Electrician
                    </a>
                    <a href="#" class="saki-search-item">
                        <i class="fa-solid fa-faucet-drip fa-c"></i> Plumbing
                    </a>
                </div>
            </div>
        </div>

    </nav>
