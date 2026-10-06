@include('front.include.header')
<title>Saki Service</title>
@include('front.include.css')


</head>

<body>

@include('front.include.navigation')



    <!-- main content start here -->
    <main class="auth-main">

        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="auth-card">

                        <!-- Left: Form -->
                        <div class="auth-form-panel">
                            <a href="#" class="brand-logo">
                              
                                <img src="{{URL::asset(asset_path('assets_front/icon/saki-logo.png'))}}" alt="Saki Services Logo" style="border-radius: 10px; width: 30%; margin: 0 auto 0 0;">
                            </a>

                            <h1 class="auth-heading">Welcome Back!</h1>
                            <p class="auth-sub">Sign in by completing the information below</p>

                            <form id="loginForm" novalidate>
                                <!-- Email -->
                                <div class="field-group">
                                    <label class="field-label" for="email">Your Email</label>
                                    <div class="input-wrap">
                                        <span class="icon">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                                <polyline points="22,6 12,13 2,6" />
                                            </svg>
                                        </span>
                                        <input type="email" id="email" name="email" class="auth-input" placeholder="you@example.com"
                                            autocomplete="email" />
                                    </div>
                                    <span class="field-error" id="email-error">Please enter a valid email address.</span>
                                </div>

                                <!-- Password -->
                                <div class="field-group">
                                    <label class="field-label" for="password">Password</label>
                                    <div class="input-wrap">
                                        <span class="icon">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"
                                                viewBox="0 0 24 24">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0110 0v4" />
                                            </svg>
                                        </span>
                                        <input type="password" id="password" name="password" class="auth-input" placeholder="••••••••"
                                            autocomplete="current-password" />
                                        <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password">
                                            <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor"
                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="field-error" id="password-error">Please enter your password.</span>
                                </div>

                                <div class="forgot-wrap">
                                    <a href="#" class="forgot-link">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn-primary-auth">Log in</button>

                                <!-- <div class="divider">or</div> -->

                                <!-- <button type="button" class="btn-google">
                                    <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 01-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z"
                                            fill="#4285F4" />
                                        <path
                                            d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z"
                                            fill="#34A853" />
                                        <path
                                            d="M3.964 10.706A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.038l3.007-2.332z"
                                            fill="#FBBC05" />
                                        <path
                                            d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z"
                                            fill="#EA4335" />
                                    </svg>
                                    Continue with Google
                                </button> -->
                            </form>

                            <p class="auth-footer">Don't have an account? <a href="{{route('register')}}">Sign Up</a></p>
                        </div>

                        <!-- Right: Image -->
                        <div class="auth-image-panel">
                            <img src="{{URL::asset(asset_path('assets_front/img/ac-servcs.jpg'))}}" alt="AC Services" id="heroImage" />
                            <div class="img-overlay"></div>
                            <div class="img-brand">
                                <img src="{{URL::asset(asset_path('assets_front/icon/saki-logo.png'))}}" alt="Saki Services Logo" style="border-radius: 10px;  width: 40%; margin: 0 0 0 auto;  background-color: #fff;  padding: 2% 4%;">
                            </div>
                            <div class="img-tagline">
                                <h2>Find your perfect home, faster.</h2>
                                <p>Thousands of listings, curated for you — all in one place.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            </diiv>

    </main>
    <!-- main content end here -->

@include('front.include.footer')
@include('front.include.js')

    <script>
        $(function() {

            /* Toggle password visibility */
            $('#togglePw').on('click', function() {
                const input = $('#password');
                const isText = input.attr('type') === 'text';
                input.attr('type', isText ? 'password' : 'text');
                $('#eyeIcon').html(isText ?
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>' :
                    '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                );
            });

            /* Validation helpers */
            function showError(fieldId, errorId, msg) {
                $('#' + fieldId).addClass('is-invalid');
                $('#' + errorId).text(msg).addClass('show');
            }

            function clearError(fieldId, errorId) {
                $('#' + fieldId).removeClass('is-invalid');
                $('#' + errorId).removeClass('show');
            }

            function validateEmail(val) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());
            }

            /* Live clear on input */
            $('#email').on('input', function() {
                clearError('email', 'email-error');
            });
            $('#password').on('input', function() {
                clearError('password', 'password-error');
            });

            /* Submit */
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                let valid = true;

                const email = $('#email').val().trim();
                const password = $('#password').val();

                if (!email) {
                    showError('email', 'email-error', 'Email is required.');
                    valid = false;
                } else if (!validateEmail(email)) {
                    showError('email', 'email-error', 'Please enter a valid email address.');
                    valid = false;
                } else {
                    clearError('email', 'email-error');
                }

                if (!password) {
                    showError('password', 'password-error', 'Password is required.');
                    valid = false;
                } else {
                    clearError('password', 'password-error');
                }

                if (valid) {
                    // ✅ Replace with real form submission / AJAX
                    alert('Login successful! (hook up your backend here)');
                }
            });

        });
    </script>
</body>

</html>