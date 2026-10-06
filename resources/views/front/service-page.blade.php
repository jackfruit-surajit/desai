@include('front.include.header')
<title>Saki Service</title>
@include('front.include.css')


</head>

<body>
@include('front.include.navigation')


    <!-- main content start here -->
    <main class="saki-main-services">
        <!-- =============== SAKI SERVICE DETAILS LAYOUT ====================== -->

        <section class="saki-service-details">
            <div class="container">

                <div class="row g-4">

                    <!-- ==========  LEFT SIDEBAR =============== -->
                    <div class="col-xl-3 col-lg-4">

                        <div class="saki-service-sidebar">

                            <!-- TITLE -->
                            <div class="saki-service-heading mb-4">
                                <h2>Bathroom Cleaning</h2>

                                <div class="saki-service-rating">
                                    ⭐ 4.83
                                    <span>(7.1 M bookings)</span>
                                </div>
                            </div>

                            <!-- CATEGORY BOX -->
                            <div class="saki-category-box">

                                <h6 class="saki-box-title">
                                    Select a service
                                </h6>

                                <div class="saki-category-grid">

                                    <div class="saki-category-card active">
                                        <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?q=80&w=200"
                                            alt="">
                                        <span>3-visit packs</span>
                                    </div>

                                    <div class="saki-category-card">
                                        <img src="https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=200"
                                            alt="">
                                        <span>Value deals</span>
                                    </div>

                                    <div class="saki-category-card">
                                        <img src="https://images.unsplash.com/photo-1600566753151-384129cf4e3e?q=80&w=200"
                                            alt="">
                                        <span>Deep clean</span>
                                    </div>

                                    <div class="saki-category-card">
                                        <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=200"
                                            alt="">
                                        <span>Mini services</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ===========  MAIN CONTENT ============ -->
                    <div class="col-xl-6 col-lg-8">

                        <div class="saki-main-content">

                            <!-- HERO IMAGE -->
                            <div class="saki-banner-image mb-4">
                                <img src="https://images.unsplash.com/photo-1620626011761-996317b8d101?q=80&w=1200"
                                    alt="">
                            </div>

                            <!-- SECTION -->
                            <div class="saki-service-section">

                                <div class="saki-section-header">
                                    <h3>3-visit packs</h3>
                                </div>

                                <!-- CARD -->
                                <div class="saki-service-card">

                                    <div class="saki-service-thumb">
                                        <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?q=80&w=800"
                                            alt="">
                                    </div>

                                    <div class="saki-service-body">

                                        <div class="saki-service-top">

                                            <div>
                                                <h4>
                                                    3 visits (Weekdays only):
                                                    Intense bathroom cleaning
                                                </h4>

                                                <div class="saki-rating-small">
                                                    ⭐ 4.80 (5.6M reviews)
                                                </div>

                                                <div class="saki-price">
                                                    Starts at ₹1,197
                                                    <del>₹1,497</del>
                                                </div>
                                            </div>

                                            <button class="saki-add-btn">
                                                Add
                                            </button>

                                        </div>

                                        <ul class="saki-feature-list">
                                            <li>Book 3 visits at discounted price</li>
                                            <li>Avail within 6 months</li>
                                        </ul>

                                        <a href="#" class="saki-view-details">
                                            View details
                                        </a>

                                    </div>

                                </div>

                                <!-- CARD -->
                                <div class="saki-service-card">

                                    <div class="saki-service-thumb">
                                        <img src="https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?q=80&w=800"
                                            alt="">
                                    </div>

                                    <div class="saki-service-body">

                                        <div class="saki-service-top">

                                            <div>
                                                <h4>
                                                    3 visits:
                                                    Intense bathroom cleaning
                                                </h4>

                                                <div class="saki-rating-small">
                                                    ⭐ 4.80 (5.6M reviews)
                                                </div>

                                                <div class="saki-price">
                                                    Starts at ₹1,347
                                                    <del>₹1,497</del>
                                                </div>
                                            </div>

                                            <button class="saki-add-btn">
                                                Add
                                            </button>

                                        </div>

                                        <ul class="saki-feature-list">
                                            <li>Floor cleaning with scrub machine</li>
                                            <li>Professional tools included</li>
                                        </ul>

                                        <a href="#" class="saki-view-details">
                                            View details
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- =======  RIGHT SIDEBAR  =============== -->
                    <div class="col-xl-3">

                        <div class="saki-right-sidebar">

                            <!-- PROMISE -->
                            <div class="saki-promise-box">

                                <h5>UC Promise</h5>

                                <ul>
                                    <li>Verified Professionals</li>
                                    <li>Hassle Free Booking</li>
                                    <li>Transparent Pricing</li>
                                </ul>

                            </div>

                            <!-- CART -->
                            <div class="saki-cart-box">

                                <div class="saki-cart-price">
                                    ₹2,694
                                    <del>₹3,594</del>
                                </div>

                                <button class="saki-cart-btn">
                                    View Cart
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </section>




        <!-- ==================  SAKI WELCOME MODAL ============================= -->

        <div class="modal fade saki-entry-modal"
            id="sakiEntryModal"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content saki-modal-content">

                    <!-- CLOSE BUTTON -->
                    <button type="button"
                        class="saki-modal-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                        ✕

                    </button>

                    <!-- MODAL BODY -->
                    <div class="saki-modal-body">

                        <!-- IMAGE -->
                        <div class="saki-modal-banner">

                            <img src="https://images.unsplash.com/photo-1620626011761-996317b8d101?q=80&w=1200"
                                alt="Bathroom Cleaning">

                        </div>

                        <!-- CONTENT -->
                        <div class="saki-modal-content-wrap">

                            <div class="saki-modal-top">

                                <div>

                                    <h3>
                                        Intense cleaning (2 bathrooms)
                                    </h3>

                                    <div class="saki-modal-rating">
                                        ⭐ 4.80 (5.6M reviews)
                                    </div>

                                    <div class="saki-modal-price">
                                        ₹913
                                        <del>₹998</del>
                                        <span>• 2 hrs</span>
                                    </div>

                                    <div class="saki-modal-tag">
                                        ₹457 per bathroom
                                    </div>

                                </div>



                            </div>

                        </div>

                        <!-- Variation Of prices COVERED -->
                        <div class="saki-services-varition">
                            <div class="variation-bx">
                                <h4>1 Bathroom</h4>
                                <span>4.80 (5.6M reviews)</span>
                                <p>₹457 <del>₹499</del></p>
                                <button class="saki-modal-add-btn">
                                    Add
                                </button>
                            </div>
                            <div class="variation-bx">
                                <h4>1 Bathroom</h4>
                                <span>4.80 (5.6M reviews)</span>
                                <p>₹457 <del>₹499</del></p>
                                <button class="saki-modal-add-btn">
                                    Add
                                </button>
                            </div>
                            <div class="variation-bx">
                                <h4>1 Bathroom</h4>
                                <span>4.80 (5.6M reviews)</span>
                                <p>₹457 <del>₹499</del></p>
                                <button class="saki-modal-add-btn">
                                    Add
                                </button>
                            </div>
                            <div class="variation-bx">
                                <h4>1 Bathroom</h4>
                                <span>4.80 (5.6M reviews)</span>
                                <p>₹457 <del>₹499</del></p>
                                <button class="saki-modal-add-btn">
                                    Add
                                </button>
                            </div>
                            <div class="variation-bx">
                                <h4>1 Bathroom</h4>
                                <span>4.80 (5.6M reviews)</span>
                                <p>₹457 <del>₹499</del></p>
                                <button class="saki-modal-add-btn">
                                    Add
                                </button>
                            </div>
                        </div>

                        <!-- WHAT COVERED -->
                        <div class="saki-modal-covered">

                            <h4>
                                What is covered
                            </h4>

                            <ul>

                                <li>
                                    ✔ Hard water stains
                                </li>

                                <li>
                                    ✔ Toilet seat from outside & inside
                                </li>

                                <li>
                                    ✔ Floor scrubbing with machine
                                </li>

                                <li>
                                    ✔ Basin & tap deep cleaning
                                </li>

                                <li>
                                    ✔ Mirror cleaning
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>



    </main>
    <!-- main content end here -->

@include('front.include.footer')
@include('front.include.js')

    <script>
        /* =====================  SHOW MODAL ON PAGE LOAD ========================== */

        $(window).on('load', function() {

            const sakiWelcomeModal = new bootstrap.Modal(
                document.getElementById('sakiEntryModal')
            );

            sakiWelcomeModal.show();

        });


        /* =====================  INIT SLICK AFTER MODAL IS FULLY OPEN ========================== */

        $('#sakiEntryModal').on('shown.bs.modal', function() {

            if (!$('.saki-services-varition').hasClass('slick-initialized')) {

                $('.saki-services-varition').slick({
                    dots: true,
                    arrows: false,
                    infinite: false,
                    speed: 500,

                    slidesToShow: 3.2,
                    slidesToScroll: 1,

                    adaptiveHeight: false,

                    responsive: [

                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },

                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }

                    ]

                });

            }

        });
    </script>


</body>

</html>