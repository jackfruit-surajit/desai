@include('front.include.header')
<title>Saki Service</title>
@include('front.include.css')

<style>
    .auth-card {
        height: 70%;
    }
    
   
</style>

</head>

<body>
@include('front.include.navigation')

    <!-- ══════════════ HERO ══════════════ -->
    <section class="section-banner">
        <div class="banner-container-slider">

            <!--First Banner content goes here -->
            <div class="hero" style="background: url('assets_front/banner/ac-service-banner.png') no-repeat; background-position: center;">
                <div class=" container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cont-lft">
                                <div class="hero-badge">
                                    <i class="fa-solid fa-circle-check"></i> Trusted by Thousands of Happy Customers
                                </div>
                                <h1>Stay Cool with <br><span>Expert AC Service</span></h1>
                                <p class="lead mt-3">Professional AC cleaning, repair, and maintenance services to keep your cooling system running efficiently and your home comfortable all year round.</p>
                                <div class="hero-trust-badges">
                                    <div class="trust-item"><i class="fa-solid fa-shield-halved fac-c"></i> Verified <br>
                                        Professionals
                                    </div>
                                    <div class="trust-item"><i class="fa-regular fa-clock fac-c"></i> On-Time <br> Service</div>
                                    <div class="trust-item"><i class="fa-solid fa-lock fac-c"></i> Secure <br> Payments</div>
                                    <div class="trust-item"><i class="fa-solid fa-headset fac-c"></i> 24/7 <br> Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center position-relative">
                            <div class="hero-img-wrap">
                                <img class="worker" src="assets_front/img/person-banner.jpg" alt="Professional technician" />
                                <div class="hero-stat-card top-right">
                                    <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="stat-num">20K+</div>
                                        <div class="stat-label">Happy Customers</div>
                                        <div class="avatar-stack mt-1">
                                            <img src="https://i.pravatar.cc/24?img=1" alt="">
                                            <img src="https://i.pravatar.cc/24?img=2" alt="">
                                            <img src="https://i.pravatar.cc/24?img=3" alt="">
                                            <img src="https://i.pravatar.cc/24?img=4" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-stat-card bottom-right">
                                    <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Average Rating</div>
                                        <div style="color:var(--secondary);font-size:0.7rem;">★★★★★</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Second Banner content goes here -->
            <div class="hero" style="background: url('assets_front/banner/plumbing-servicing.png') no-repeat;background-position: top;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cont-lft">
                                <div class="hero-badge">
                                    <i class="fa-solid fa-circle-check"></i> Trusted by Thousands of Happy Customers
                                </div>
                                <h1>Fast & Reliable<br><span>Plumbing Solutions</span></h1>
                                <p class="lead mt-3">Whether it's a leaking pipe, blocked drain, or installation work, our skilled plumbers provide quick and dependable service you can trust.</p>
                                <div class="hero-trust-badges">
                                    <div class="trust-item"><i class="fa-solid fa-shield-halved fac-c"></i> Verified <br>
                                        Professionals
                                    </div>
                                    <div class="trust-item"><i class="fa-regular fa-clock fac-c"></i> On-Time <br> Service</div>
                                    <div class="trust-item"><i class="fa-solid fa-lock fac-c"></i> Secure <br> Payments</div>
                                    <div class="trust-item"><i class="fa-solid fa-headset fac-c"></i> 24/7 <br> Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center position-relative">
                            <div class="hero-img-wrap">
                                <img class="worker" src="assets_front/img/person-banner.jpg" alt="Professional technician" />
                                <div class="hero-stat-card top-right">
                                    <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="stat-num">20K+</div>
                                        <div class="stat-label">Happy Customers</div>
                                        <div class="avatar-stack mt-1">
                                            <img src="https://i.pravatar.cc/24?img=1" alt="">
                                            <img src="https://i.pravatar.cc/24?img=2" alt="">
                                            <img src="https://i.pravatar.cc/24?img=3" alt="">
                                            <img src="https://i.pravatar.cc/24?img=4" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-stat-card bottom-right">
                                    <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Average Rating</div>
                                        <div style="color:var(--secondary);font-size:0.7rem;">★★★★★</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
             <!--Third Banner content goes here -->
            <div class="hero" style="background: url('assets_front/banner/pest-contol-service-banner.png') no-repeat;background-position: center;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cont-lft">
                                <div class="hero-badge">
                                    <i class="fa-solid fa-circle-check"></i> Trusted by Thousands of Happy Customers
                                </div>
                                <h1>A Pest-Free <br><span>Home Starts Here</span></h1>
                                <p class="lead mt-3">Safe and effective pest control solutions for homes and businesses. Eliminate unwanted pests and enjoy a cleaner, healthier environment.</p>
                                <div class="hero-trust-badges">
                                    <div class="trust-item"><i class="fa-solid fa-shield-halved fac-c"></i> Verified <br>
                                        Professionals
                                    </div>
                                    <div class="trust-item"><i class="fa-regular fa-clock fac-c"></i> On-Time <br> Service</div>
                                    <div class="trust-item"><i class="fa-solid fa-lock fac-c"></i> Secure <br> Payments</div>
                                    <div class="trust-item"><i class="fa-solid fa-headset fac-c"></i> 24/7 <br> Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center position-relative">
                            <div class="hero-img-wrap">
                                <img class="worker" src="assets_front/img/person-banner.jpg" alt="Professional technician" />
                                <div class="hero-stat-card top-right">
                                    <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="stat-num">20K+</div>
                                        <div class="stat-label">Happy Customers</div>
                                        <div class="avatar-stack mt-1">
                                            <img src="https://i.pravatar.cc/24?img=1" alt="">
                                            <img src="https://i.pravatar.cc/24?img=2" alt="">
                                            <img src="https://i.pravatar.cc/24?img=3" alt="">
                                            <img src="https://i.pravatar.cc/24?img=4" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-stat-card bottom-right">
                                    <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Average Rating</div>
                                        <div style="color:var(--secondary);font-size:0.7rem;">★★★★★</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
             <!--Fourth Banner content goes here -->
            <div class="hero" style="background: url('assets_front/banner/home-cleaning-service.png') no-repeat;background-position: top;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cont-lft">
                                <div class="hero-badge">
                                    <i class="fa-solid fa-circle-check"></i> Trusted by Thousands of Happy Customers
                                </div>
                                <h1>Sparkling Clean <br><span>Spaces, Every Time</span></h1>
                                <p class="lead mt-3">From deep cleaning to routine maintenance, our expert cleaners ensure every corner of your room is spotless, fresh, and welcoming.</p>
                                <div class="hero-trust-badges">
                                    <div class="trust-item"><i class="fa-solid fa-shield-halved fac-c"></i> Verified <br>
                                        Professionals
                                    </div>
                                    <div class="trust-item"><i class="fa-regular fa-clock fac-c"></i> On-Time <br> Service</div>
                                    <div class="trust-item"><i class="fa-solid fa-lock fac-c"></i> Secure <br> Payments</div>
                                    <div class="trust-item"><i class="fa-solid fa-headset fac-c"></i> 24/7 <br> Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center position-relative">
                            <div class="hero-img-wrap">
                                <img class="worker" src="assets_front/img/person-banner.jpg" alt="Professional technician" />
                                <div class="hero-stat-card top-right">
                                    <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="stat-num">20K+</div>
                                        <div class="stat-label">Happy Customers</div>
                                        <div class="avatar-stack mt-1">
                                            <img src="https://i.pravatar.cc/24?img=1" alt="">
                                            <img src="https://i.pravatar.cc/24?img=2" alt="">
                                            <img src="https://i.pravatar.cc/24?img=3" alt="">
                                            <img src="https://i.pravatar.cc/24?img=4" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-stat-card bottom-right">
                                    <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Average Rating</div>
                                        <div style="color:var(--secondary);font-size:0.7rem;">★★★★★</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
             <!--Fifth Banner content goes here -->
            <div class="hero" style="background: url('assets_front/banner/washing-machine-servicing.png') no-repeat;background-position: top;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cont-lft">
                                <div class="hero-badge">
                                    <i class="fa-solid fa-circle-check"></i> Trusted by Thousands of Happy Customers
                                </div>
                                <h1>Keep Your Washer<br><span> Running Like New</span></h1>
                                <p class="lead mt-3">Expert washing machine repair and maintenance services to ensure optimal performance, longer lifespan, and hassle-free laundry days.</p>
                                <div class="hero-trust-badges">
                                    <div class="trust-item"><i class="fa-solid fa-shield-halved fac-c"></i> Verified <br>
                                        Professionals
                                    </div>
                                    <div class="trust-item"><i class="fa-regular fa-clock fac-c"></i> On-Time <br> Service</div>
                                    <div class="trust-item"><i class="fa-solid fa-lock fac-c"></i> Secure <br> Payments</div>
                                    <div class="trust-item"><i class="fa-solid fa-headset fac-c"></i> 24/7 <br> Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center position-relative">
                            <div class="hero-img-wrap">
                                <img class="worker" src="assets_front/img/person-banner.jpg" alt="Professional technician" />
                                <div class="hero-stat-card top-right">
                                    <div class="stat-icon orange"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="stat-num">20K+</div>
                                        <div class="stat-label">Happy Customers</div>
                                        <div class="avatar-stack mt-1">
                                            <img src="https://i.pravatar.cc/24?img=1" alt="">
                                            <img src="https://i.pravatar.cc/24?img=2" alt="">
                                            <img src="https://i.pravatar.cc/24?img=3" alt="">
                                            <img src="https://i.pravatar.cc/24?img=4" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-stat-card bottom-right">
                                    <div class="stat-icon yellow"><i class="fa-solid fa-star"></i></div>
                                    <div>
                                        <div class="stat-num">4.8</div>
                                        <div class="stat-label">Average Rating</div>
                                        <div style="color:var(--secondary);font-size:0.7rem;">★★★★★</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>






    <!-- ══════════════ SERVICES ══════════════ -->
    <section class="saki-category-sec">

        <div class="container">

            <div class="saki-section-title text-center">
                <div class="section-label">Popular Services</div>
                <h2 class="section-title mt-1">What Can We Help You With?</h2>
            </div>

            <div class="saki-category-slider">

                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/insta-help.png" alt="AC Repair">
                    </div>

                    <h4>Insta Help</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/woman-spa.png" alt="AC Repair">
                    </div>

                    <h4>Women's Saloon </br> & Spa</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/man-spa.png" alt="AC Repair">
                    </div>

                    <h4>Men's Spa </br> & Massage</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/cleanin.png" alt="AC Repair">
                    </div>

                    <h4>Home Cleaning</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/plumber.png" alt="AC Repair">
                    </div>

                    <h4>Plumber Service</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/electrician-services.png" alt="AC Repair">
                    </div>

                    <h4>Electrician Service</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/ac-service.png" alt="AC Repair">
                    </div>

                    <h4>AC Service</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/washing-machine.png" alt="AC Repair">
                    </div>

                    <h4>Washing Machine Service</h4>

                </div>
                  <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/microwave.png" alt="AC Repair">
                    </div>

                    <h4>Microwave Machine Service</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/cleaning-services.png" alt="AC Repair">
                    </div>

                    <h4>Kitchen Cleaning Service</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/pest-control.png" alt="AC Repair">
                    </div>

                    <h4>Pest Control Services</h4>

                </div>
                <div class="saki-category-card">

                    <div class="saki-category-icon">
                        <img src="assets_front/icon/refrigereator.png" alt="AC Repair">
                    </div>

                    <h4>Refrigerator Services</h4>

                </div>

            </div>

        </div>

    </section>




    <section class="saki-featured-sec">

        <div class="container">

            <div class="saki-section-title text-center">
                <div class="section-label">Featured Services</div>
                <h2 class="section-title mt-1">Most Booked Services</h2>
            </div>

            <!-- =======================
                    AC SERVICES
                ======================== -->

            <div class="saki-featured-wrap" id="ac-services">

                <div class="saki-featured-head">

                    <h3>AC Services</h3>

                    <a href="#">
                        View All
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

                <div class="saki-featured-slider">

                    <!-- ITEM -->

                    <div class="saki-featured-card">
                        <a href="service-page.php">
                            <div class="saki-featured-img">
                                <img src="assets_front/img/outdoor-ac.webp" alt="AC Repair">
                            </div>

                            <div class="saki-featured-content">

                                <h4>Outdoor AC Service</h4>

                                <p>
                                    Fast cooling repair solutions.
                                </p>

                                <div class="saki-featured-bottom">

                                    <span>₹499</span>

                                    <a href="#">
                                        Add New
                                    </a>

                                </div>

                            </div>
                        </a>
                    </div>

                    <!-- ITEM -->

                    <div class="saki-featured-card">
                        <a href="service-page.php">
                            <div class="saki-featured-img">
                                <img src="assets_front/img/ac-repair.jpeg" alt="Indoor AC Service">
                            </div>

                            <div class="saki-featured-content">

                                <h4>Indoor AC Cleaning</h4>

                                <p>
                                    Deep indoor unit cleaning service.
                                </p>

                                <div class="saki-featured-bottom">

                                    <span>₹799</span>

                                    <a href="#">
                                        Add New
                                    </a>

                                </div>

                            </div>
                        </a>
                    </div>

                    <!-- ITEM -->

                    <div class="saki-featured-card">
                        <a href="service-page.php">
                            <div class="saki-featured-img">
                                <img src="assets_front/img/window-ac.webp" alt="Window AC">
                            </div>

                            <div class="saki-featured-content">

                                <h4>Window AC Service</h4>

                                <p>
                                    Complete window AC maintenance.
                                </p>

                                <div class="saki-featured-bottom">

                                    <span>₹699</span>

                                    <a href="#">
                                        Add New
                                    </a>

                                </div>

                            </div>
                        </a>
                    </div>

                    <!-- ITEM -->

                    <div class="saki-featured-card">
                        <a href="service-page.php">
                            <div class="saki-featured-img">
                                <img src="assets_front/img/ac-installation.webp" alt="AC Installation">
                            </div>

                            <div class="saki-featured-content">

                                <h4>AC Installation</h4>

                                <p>
                                    Professional AC installation service.
                                </p>

                                <div class="saki-featured-bottom">

                                    <span>₹399</span>

                                    <a href="#">
                                        Add New
                                    </a>

                                </div>

                            </div>
                        </a>
                    </div>

                    <!-- ITEM -->

                    <div class="saki-featured-card">
                        <a href="service-page.php">
                            <div class="saki-featured-img">
                                <img src="assets_front/img/gas-refill.jpg" alt="Gas refill & Check-up">
                            </div>

                            <div class="saki-featured-content">

                                <h4>Gas refill & Check-up</h4>

                                <p>
                                    Lorem ipsum dolor sit amet.
                                </p>

                                <div class="saki-featured-bottom">

                                    <span>₹399</span>

                                    <a href="#">
                                        Add New
                                    </a>

                                </div>

                            </div>
                        </a>
                    </div>

                </div>

            </div>

            <!-- =======================
                    CLEANING SERVICES
                ======================== -->

            <div class="saki-featured-wrap" id="cleaning-services">

                <div class="saki-featured-head">

                    <h3>Cleaning Services</h3>

                    <a href="#">
                        View All
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

                <div class="saki-featured-slider">

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?q=80&w=1400&auto=format&fit=crop"
                                alt="Home Cleaning">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Home Cleaning</h4>

                            <p>
                                Complete home deep cleaning.
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹1499</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?q=80&w=1000&auto=format&fit=crop"
                                alt="Bathroom Cleaning">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Bathroom Cleaning</h4>

                            <p>
                                Professional bathroom sanitization.
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹899</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="https://images.unsplash.com/photo-1556911220-bff31c812dba?q=80&w=1000&auto=format&fit=crop"
                                alt="Kitchen Cleaning">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Kitchen Cleaning</h4>

                            <p>
                                Deep kitchen cleaning solutions.
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹1199</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- ITEM -->

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1000&auto=format&fit=crop"
                                alt="Sofa Cleaning">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Sofa Cleaning</h4>

                            <p>
                                Expert upholstery & sofa cleaning.
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹999</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- =======================
                    PAINTING SERVICES
                ======================== -->

            <div class="saki-featured-wrap" id="painting-services">

                <div class="saki-featured-head">

                    <h3>SPA & Saloon Services</h3>

                    <a href="#">
                        View All
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

                <div class="saki-featured-slider">

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="assets_front/img/saloon-service.jpg" alt="Wall Painting">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Nail art</h4>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc
                                luctus
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹2499</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="assets_front/img/spa-service.jpg" alt="Texture Painting">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Anti-aging facial</h4>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc
                                luctus
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹3499</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="assets_front/img/saloon-make.webp" alt="saloon">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Detox treatments</h4>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc
                                luctus
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹1299</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="assets_front/img/Manicure.jpg" alt="Manicure">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Manicure</h4>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc
                                luctus
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹1099</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="saki-featured-card">

                        <div class="saki-featured-img">
                            <img src="assets_front/img/hair-color.webp" alt="hair color">
                        </div>

                        <div class="saki-featured-content">

                            <h4>Hair Coloring</h4>

                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel sapien eget nunc
                                luctus
                            </p>

                            <div class="saki-featured-bottom">

                                <span>₹899</span>

                                <a href="#">
                                    Add New
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>







    <!-- ══════════════ HOW IT WORKS ══════════════ -->
    <section class="how-section" id="how-it-works">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="section-label">HOW IT WORKS</div>
                    <h2 class="section-title mt-1">Simple Steps to<br>Book Your Service</h2>
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                        <div class="step-box">
                            <div class="step-icon-wrap"><i class="fa-solid fa-magnifying-glass i-cs"></i></div>
                            <p>Choose<br>Your Service</p>
                        </div>
                        <div class="step-connector d-none d-sm-block">
                            <img src="assets_front/icon/right.png" alt="">
                        </div>
                        <div class="step-box">
                            <div class="step-icon-wrap"><i class="fa-solid fa-calendar-days i-cs"
                                    style="color:var(--secondary);"></i></div>
                            <p>Pick Date<br>&amp; Time</p>
                        </div>
                        <div class="step-connector d-none d-sm-block">
                            <img src="assets_front/icon/right.png" alt="">
                        </div>
                        <div class="step-box">
                            <div class="step-icon-wrap">
                                <img src="assets_front/icon/booking.png" alt="">
                            </div>
                            <p>Confirm<br>Booking</p>
                        </div>
                        <div class="step-connector d-none d-sm-block">
                            <img src="assets_front/icon/right.png" alt="">
                        </div>
                        <div class="step-box">
                            <div class="step-icon-wrap"><i class="fa-solid fa-circle-check i-cs" style="color:#25d366;"></i>
                            </div>
                            <p>We Arrive &amp;<br>Get It Done</p>
                        </div>
                    </div>
                    <button class="btn-secondary-outline" id="bookNowBtn">
                        Book Your Service &nbsp;<i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
                <div class="col-lg-5">
                    <img src="assets_front/img/how-it-workspng.png" alt="Service provider at home" class="how-img shadow" />
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════ WHY CHOOSE ══════════════ -->
    <section class="why-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Why Choose Saki Services?</h2>
            </div>
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-blue-soft mx-auto"><i class="fa-solid fa-user-shield c-blue"></i></div>
                        <h6>Verified Professionals</h6>
                        <p>Background verified &amp; experienced</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-orange-soft mx-auto"><i class="fa-regular fa-clock c-orange"></i></div>
                        <h6>On-Time Service</h6>
                        <p>We value your time &amp; punctuality</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-purple-soft mx-auto"><i class="fa-solid fa-tag c-purple"></i></div>
                        <h6>Transparent Pricing</h6>
                        <p>No hidden charges, 100% transparency</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-green-soft mx-auto"><i class="fa-solid fa-shield-halved c-green"></i>
                        </div>
                        <h6>Secure Payments</h6>
                        <p>Safe &amp; secure online payments</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-pink-soft mx-auto"><i class="fa-solid fa-award c-pink"></i></div>
                        <h6>Quality Guaranteed</h6>
                        <p>Satisfaction is our promise</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="why-card">
                        <div class="why-icon bg-teal-soft mx-auto"><i class="fa-solid fa-headset c-teal"></i></div>
                        <h6>24/7 Support</h6>
                        <p>We're here for you anytime</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════ STATS STRIP ══════════════ -->
    <section class="stats-strip" id="stats-strip">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-block">
                        <div class="stat-icon-big"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-big counter" data-target="20000" data-suffix="K+" data-divisor="1000">0K+</div>
                        <div class="stat-lbl">Happy Customers</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-block">
                        <div class="stat-icon-big"><i class="fa-solid fa-house-chimney-user"></i></div>
                        <div class="stat-big counter" data-target="5000" data-suffix="K+" data-divisor="1000">0K+</div>
                        <div class="stat-lbl">Verified Professionals</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-block">
                        <div class="stat-icon-big"><i class="fa-solid fa-list-check"></i></div>
                        <div class="stat-big counter" data-target="15000" data-suffix="K+" data-divisor="1000">0K+</div>
                        <div class="stat-lbl">Services Completed</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-block">
                        <div class="stat-icon-big"><i class="fa-solid fa-star"></i></div>
                        <div class="stat-big counter" data-target="48" data-suffix="/5" data-divisor="10">0/5</div>
                        <div class="stat-lbl">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="opacity"></div>
    </section>


    <!-- ══════════════ TESTIMONIALS ══════════════ -->
    <section class="community-section w-100">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row align-items-lg-stretch">

                <!-- Left -->
                <div class="community-left col-lg-4">
                    <h2>From our<br><strong>community.</strong></h2>
                    <p>Here's what our customers had to say about Saki Services.</p>
                </div>

                <!-- Divider -->
                <div class="community-divider d-none d-lg-block"></div>

                <!-- Right — Slick Slider -->
                <div class="community-right col-lg-8">
                    <span class="quote-mark">&ldquo;</span>

                    <div class="testimonial-slider">

                        <div class="testimonial-slide">
                            <blockquote>Saki Services has completely changed how I manage home repairs. The professionals are
                                top-notch and always on time.</blockquote>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/100?img=11" alt="Rohit Sharma" />
                                <div>
                                    <div class="reviewer-name">Rohit Sharma</div>
                                    <div class="reviewer-role">Homeowner, Bangalore</div>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-slide">
                            <blockquote>Booked an AC service on a Sunday and the technician arrived within 2 hours.
                                Absolutely brilliant service!</blockquote>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/100?img=32" alt="Priya Nair" />
                                <div>
                                    <div class="reviewer-name">Priya Nair</div>
                                    <div class="reviewer-role">Working Professional, Chennai</div>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-slide">
                            <blockquote>Transparent pricing, no surprises. I've used Saki Services three times now and each
                                experience has been flawless.</blockquote>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/100?img=53" alt="Arjun Mehta" />
                                <div>
                                    <div class="reviewer-name">Arjun Mehta</div>
                                    <div class="reviewer-role">Business Owner, Mumbai</div>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-slide">
                            <blockquote>The verified professionals gave me real peace of mind. I finally feel safe
                                letting someone into my home for repairs.</blockquote>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/100?img=44" alt="Sneha Iyer" />
                                <div>
                                    <div class="reviewer-name">Sneha Iyer</div>
                                    <div class="reviewer-role">Resident, Hyderabad</div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.testimonial-slider -->
                </div>

            </div>
        </div>
    </section>


    <!-- ══════════════ CTA ══════════════ -->
    <section class="cta-section">

        <!-- Floating avatars -->
        <div class="cta-avatar" style="top:12%;left:7%;">
            <img src="assets_front/img/ac-repair.jpeg" alt="">
        </div>
        <div class="cta-avatar cta-avatar--lg" style="top:6%;left:15%;">
            <img src="assets_front/img/ac-installation.webp" alt="">
        </div>
        <div class="cta-avatar" style="top:52%;left:6%;">
            <img src="assets_front/img/cleaning-service.jpg" alt="">
        </div>
        <div class="cta-avatar cta-avatar--sm" style="bottom:10%;left:3%;">
            <img src="assets_front/img/saloon-make.webp" alt="">
        </div>
        <div class="cta-avatar cta-avatar--sm" style="bottom:14%;left:15%;">
            <img src="assets_front/img/spa-service.jpg" alt="">
        </div>

        <div class="cta-avatar cta-avatar--lg" style="top:5%;right:18%;">
            <img src="assets_front/img/Manicure.jpg" alt="">
        </div>
        <div class="cta-avatar cta-avatar--sm" style="top:8%;right:6%;">
            <img src="assets_front/img/window-ac.webp" alt="">
        </div>
        <div class="cta-avatar" style="top:48%;right:5%;">
            <img src="assets_front/img/hair-color.webp" alt="">
        </div>
        <div class="cta-avatar cta-avatar--lg" style="bottom:8%;right:14%;">
            <img src="assets_front/img/repair-service.jpg" alt="">
        </div>
        <div class="cta-avatar" style="bottom:8%;right:3%;">
            <img src="assets_front/img/ac-repair.png" alt="">
        </div>

        <!-- Center content -->
        <div class="cta-content">
            <h2 class="cta-heading">Ready to get started?</h2>
            <p class="cta-subtext">
                Lorem ipsum dolor sit amet. Et blanditiis voluptatem est reprehenderit
                laborum cum distinctio voluptas id excepturi possimus ea delectus
                tenetur aut totam officia aut nostrum minus.
            </p>
            <a href="#" class="cta-btn">Get Started &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
        </div>

    </section>

@include('front.include.footer')
@include('front.include.js')

</body>

</html>