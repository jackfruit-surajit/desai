$(function () {

    $('.banner-container-slider').slick({
        dots: true,
        arrows: false, infinite: true,
        speed: 900, autoplay: true, autoplaySpeed: 3000,
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
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
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    // if ($('').length) {
    //     $('.saki-category-slider').slick({
    //         slidesToShow: 7, slidesToScroll: 1,
    //         arrows: false, dots: true, infinite: true,
    //         speed: 800, autoplay: true, autoplaySpeed: 2500,
    //         prevArrow: '<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
    //         nextArrow: '<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',
    //         responsive: [
    //             { breakpoint: 1200, settings: { slidesToShow: 5 } },
    //             { breakpoint: 992, settings: { slidesToShow: 4 } },
    //             { breakpoint: 768, settings: { slidesToShow: 4 } },
    //             { breakpoint: 576, settings: { slidesToShow: 3.2,slidesToScroll: 1 } }
    //         ]
    //     });
    // }

    /* ── 2. SERVICES SLIDER (Slick) ── */
    if ($('.saki-featured-slider').length) {
        $('.saki-featured-slider').slick({
            slidesToShow: 5, slidesToScroll: 1,
            arrows: true, dots: false, infinite: true,
            prevArrow: '<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
            nextArrow: '<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',
            responsive: [
                { breakpoint: 992, settings: { slidesToShow: 3 } },
                { breakpoint: 576,  settings: {  arrows: false, dots:true, slidesToShow: 2.2,slidesToScroll: 1 } }
            ]
        });
    }

  

    /* ── 3. TESTIMONIAL SLIDER (Slick) ── */
    if ($('.testimonial-slider').length) {
        $('.testimonial-slider').slick({
            dots: true, arrows: false, infinite: true, speed: 450,
            slidesToShow: 1, slidesToScroll: 1,
            autoplay: true, autoplaySpeed: 6000,
            pauseOnHover: true, swipe: true, adaptiveHeight: true,
            prevArrow: '<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
            nextArrow: '<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>'
        });
    }

    /* ── NAVBAR SCROLL BACKGROUND ── */
    function toggleNavbarScroll() {
        if ($(window).scrollTop() > 10) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    }

    /* Run on load */
    toggleNavbarScroll();

    /* Run on scroll */
    $(window).on('scroll', toggleNavbarScroll);

    /* ── 4. SMOOTH SCROLL ── */
    $('a[href^="#"]').on('click', function (e) {
        const target = $($(this).attr('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: target.offset().top - 70 }, 500);
        }
    });
    if ($('#bookNowBtn').length) {
        $('#bookNowBtn').on('click', function () {
            $('html, body').animate({ scrollTop: $('#services').offset().top - 70 }, 500);
        });
    }

    /* ── 5. ACTIVE NAV ON SCROLL ── */
    $(window).on('scroll', function () {
        let current = '';
        $('section[id]').each(function () {
            if ($(window).scrollTop() >= $(this).offset().top - 80) current = $(this).attr('id');
        });
        $('.nav-link').removeClass('active');
        $('.nav-link[href="#' + current + '"]').addClass('active');
    });

    /* ── 6. ANIMATED COUNTERS ── */
    const $strip = $('#stats-strip');
    if ($strip.length) {
        let counted = false;
        function runCounters() {
            if (counted) return;
            counted = true;
            $('.counter').each(function () {
                const $el = $(this);
                const target = parseInt($el.data('target'), 10);
                const suffix = $el.data('suffix') || '';
                const divisor = parseFloat($el.data('divisor')) || 1;
                const steps = 60;
                const stepTime = 2000 / steps;
                let current = 0;
                const inc = target / steps;
                const timer = setInterval(function () {
                    current += inc;
                    if (current >= target) { current = target; clearInterval(timer); }
                    const display = divisor > 1
                        ? (current / divisor).toFixed(divisor === 10 ? 1 : 0)
                        : Math.floor(current);
                    $el.text(display + suffix);
                }, stepTime);
            });
        }
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) { runCounters(); obs.disconnect(); }
            }, { threshold: 0.3 });
            obs.observe($strip[0]);
        } else {
            runCounters();
        }
    }

    /* ── 7. DESKTOP LIVE SEARCH ── */
    const $input = $('#sakiSearchInput');
    const $dropdown = $('#sakiSearchDropdown');
    const $items = $('#sakiSearchDropdown .saki-search-item');
    const $clearBtn = $('#sakiClearBtn');

    function filterItems($itemSet, val) {
        $itemSet.each(function () {
            $(this).toggle($(this).text().toLowerCase().includes(val));
        });
    }

    $input.on('input', function () {
        const val = $(this).val().toLowerCase().trim();
        $clearBtn.css('display', val.length > 0 ? 'block' : 'none');
        filterItems($items, val);
        $dropdown.toggleClass('active', val.length > 0);
    });
    $input.on('focus', function () {
        if ($(this).val().trim()) $dropdown.addClass('active');
    });
    $clearBtn.on('click', function () {
        $input.val('').trigger('input').focus();
    });

    /* ── 8. MOBILE LIVE SEARCH ── */
    const $mInput = $('#sakiMobileSearchInput');
    const $mDropdown = $('#sakiMobileSearchDropdown');
    const $mItems = $('#sakiMobileSearchDropdown .saki-search-item');
    const $mClearBtn = $('#sakiMobileClearBtn');

    $mInput.on('input', function () {
        const val = $(this).val().toLowerCase().trim();
        $mClearBtn.css('display', val.length > 0 ? 'block' : 'none');
        filterItems($mItems, val);
        $mDropdown.toggleClass('active', val.length > 0);
    });
    $mInput.on('focus', function () {
        if ($(this).val().trim()) $mDropdown.addClass('active');
    });
    $mClearBtn.on('click', function () {
        $mInput.val('').trigger('input').focus();
    });

    /* Close search dropdown on outside click */
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#sakiSearchInput, #sakiSearchDropdown, #sakiClearBtn').length) {
            $dropdown.removeClass('active');
        }
        if (!$(e.target).closest('#sakiMobileSearchInput, #sakiMobileSearchDropdown, #sakiMobileClearBtn').length) {
            $mDropdown.removeClass('active');
        }
    });

    /* ── 9. DESKTOP USER DROPDOWN ── */
    $('#sakiUserBtn').on('click', function (e) {
        e.stopPropagation();
        $('#sakiUserMenu').toggleClass('active');
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#sakiUserBtn, #sakiUserMenu').length) {
            $('#sakiUserMenu').removeClass('active');
        }
    });

    /* ── 10. MOBILE SEARCH TOGGLE ── */
    $('#sakiMobileSearchBtn').on('click', function (e) {
        e.stopPropagation();
        const $bar = $('#sakiMobileSearchBar');
        $bar.toggleClass('active');
        if ($bar.hasClass('active')) {
            // Small delay so the slide animation plays before focusing
            setTimeout(function () { $('#sakiMobileSearchInput').focus(); }, 350);
        } else {
            // Reset when closing
            $mInput.val('').trigger('input');
        }
    });

    /* ── 11. MOBILE DRAWER ── */
    function openDrawer() {
        $('#sakiMobileDrawer').addClass('active');
        $('#sakiMobileOverlay').addClass('active');
        $('body').css('overflow', 'hidden'); // prevent body scroll
    }

    function closeDrawer() {
        $('#sakiMobileDrawer').removeClass('active');
        $('#sakiMobileOverlay').removeClass('active');
        $('body').css('overflow', '');
    }

    // Open on hamburger click
    $('#sakiHamburger').on('click', function (e) {
        e.stopPropagation();
        openDrawer();
    });

    // Close on X button
    $('#sakiDrawerClose').on('click', closeDrawer);

    // Close on overlay click
    $('#sakiMobileOverlay').on('click', closeDrawer);

    // ── 12. DRAWER: Services sub-menu toggle ──
    // Use mousedown instead of click to fire before Bootstrap's click handler,
    // and stop all propagation so Bootstrap's Dropdown plugin never sees it.
    $('#drawerServicesToggle').on('click mousedown', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
    });

    // The actual toggle — on pointerup so it feels instant on touch
    $(document).on('click', '#drawerServicesToggle', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const $menu = $('#drawerServicesMenu');
        const $toggle = $(this);
        if ($menu.hasClass('show')) {
            $menu.slideUp(200, function () { $menu.removeClass('show').css('display', ''); });
            $toggle.removeClass('active');
        } else {
            $menu.addClass('show').hide().slideDown(220);
            $toggle.addClass('active');
        }
    });

}); // end $(function)