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

                        <!-- Left: Image panel -->
                        <div class="auth-image-panel">
                            <img src="{{URL::asset(asset_path('assets_front/img/spa-registration.jpg'))}}" alt="Modern city buildings" id="heroImage" />
                            <div class="img-overlay"></div>
                            <div class="img-brand left">
                                <img src="{{URL::asset(asset_path('assets_front/icon/saki-logo.png'))}}" alt="Saki Services Logo" style="width: 15%;border-radius: 10px;  width: 40%; background-color: #fff;  padding: 2% 4%;">
                            </div>
                            <div class="img-tagline">
                                <h2>Your dream home is one click away.</h2>
                                <p>Join thousands of happy homeowners who found their perfect match with HomeVista.</p>
                            </div>
                        </div>

                        <!-- Right: Form panel -->
                        <div class="auth-form-panel">
                            <h1 class="auth-heading">Create Your Account</h1>
                            <p class="auth-sub">Fill in the details below to get started</p>

                            <form id="registerForm" novalidate>

                                <!-- First + Last Name -->
                                <div class="field-row">
                                    <div class="field-group">
                                        <label class="field-label" for="firstName">First Name</label>
                                        <div class="input-wrap">
                                            <span class="icon">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                                    <circle cx="12" cy="7" r="4" />
                                                </svg>
                                            </span>
                                            <input type="text" id="firstName" name="firstName" class="auth-input" placeholder="John" autocomplete="given-name" />
                                        </div>
                                        <span class="field-error" id="firstName-error">First name is required.</span>
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label" for="lastName">Last Name</label>
                                        <div class="input-wrap">
                                            <span class="icon">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                                    <circle cx="12" cy="7" r="4" />
                                                </svg>
                                            </span>
                                            <input type="text" id="lastName" name="lastName" class="auth-input" placeholder="Doe" autocomplete="family-name" />
                                        </div>
                                        <span class="field-error" id="lastName-error">Last name is required.</span>
                                    </div>
                                </div>


                                <div class="field-row">
                                    <!-- Email -->
                                    <div class="field-group">
                                        <label class="field-label" for="email">Email Address</label>
                                        <div class="input-wrap">
                                            <span class="icon">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                                    <polyline points="22,6 12,13 2,6" />
                                                </svg>
                                            </span>
                                            <input type="email" id="email" name="email" class="auth-input" placeholder="you@example.com" autocomplete="email" />
                                        </div>
                                        <span class="field-error" id="email-error">Please enter a valid email address.</span>
                                    </div>

                                    <!-- Phone -->
                                    <div class="field-group">
                                        <label class="field-label" for="phone">
                                            Phone Number <span style="color:var(--hv-muted);font-weight:400">(optional)</span>
                                        </label>
                                        <div class="input-wrap">
                                            <span class="icon">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.09 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z" />
                                                </svg>
                                            </span>
                                            <input type="tel" id="phone" name="phone" class="auth-input" placeholder="98765 43210" autocomplete="tel" maxlength="10" />
                                        </div>
                                        <span class="field-error" id="phone-error">Enter a valid 10-digit mobile number.</span>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="field-group">
                                    <label class="field-label" for="password">Password</label>
                                    <div class="input-wrap">
                                        <span class="icon">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0110 0v4" />
                                            </svg>
                                        </span>
                                        <input type="password" id="password" name="password" class="auth-input" placeholder="Min. 8 characters" autocomplete="new-password" />
                                        <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password">
                                            <svg id="eyeIcon" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="strength-label" id="strengthLabel"></div>
                                    <span class="field-error" id="password-error">Password must be at least 8 characters.</span>
                                </div>

                                <!-- Confirm Password -->
                                <div class="field-group">
                                    <label class="field-label" for="confirmPassword">Confirm Password</label>
                                    <div class="input-wrap">
                                        <span class="icon">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0110 0v4" />
                                            </svg>
                                        </span>
                                        <input type="password" id="confirmPassword" name="confirmPassword" class="auth-input" placeholder="Re-enter your password" autocomplete="new-password" />
                                        <button type="button" class="toggle-pw" id="toggleConfirm" aria-label="Toggle confirm password">
                                            <svg id="eyeIconConfirm" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="field-error" id="confirmPassword-error">Passwords do not match.</span>
                                </div>

                                <!-- Terms -->
                                <div class="terms-row">
                                    <input type="checkbox" id="terms" name="terms" />
                                    <label for="terms">I agree to HomeVista's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                                </div>
                                <span class="terms-error" id="terms-error">You must accept the terms to continue.</span>

                                <button type="submit" class="btn-primary-auth">Create Account</button>

                                <!-- <div class="divider">or</div> -->

                                <!-- <button type="button" class="btn-google">
                  <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 01-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/>
                    <path d="M3.964 10.706A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/>
                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
                  </svg>
                  Continue with Google
                </button> -->

                                <p class="auth-footer">Already have an account? <a href="{{route('login')}}">Log In</a></p>

                            </form><!-- /registerForm -->
                        </div><!-- /auth-form-panel -->

                    </div><!-- /auth-card -->

                </div>
            </div>
        </div>
    </main>


@include('front.include.footer')
@include('front.include.js')

    <script>
        $(function() {
            function makeToggle(btnId, inputId, iconId) {
                $('#' + btnId).on('click', function() {
                    const input = $('#' + inputId);
                    const isText = input.attr('type') === 'text';
                    input.attr('type', isText ? 'password' : 'text');
                    $('#' + iconId).html(isText ?
                        '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>' :
                        '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                    );
                });
            }
            makeToggle('togglePw', 'password', 'eyeIcon');
            makeToggle('toggleConfirm', 'confirmPassword', 'eyeIconConfirm');

            function showError(fieldId, errorId, msg) {
                $('#' + fieldId).addClass('is-invalid');
                $('#' + errorId).text(msg).addClass('show');
            }

            function clearError(fieldId, errorId) {
                $('#' + fieldId).removeClass('is-invalid');
                $('#' + errorId).removeClass('show');
            }

            function validateEmail(v) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim());
            }

            const strengthColors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const strengthLabels = ['Weak', 'Fair', 'Good', 'Strong'];

            function calcStrength(pw) {
                let score = 0;
                if (pw.length >= 8) score++;
                if (pw.length >= 12) score++;
                if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
                if (/[0-9]/.test(pw)) score++;
                if (/[^A-Za-z0-9]/.test(pw)) score++;
                return Math.min(4, Math.max(0, Math.ceil(score / 5 * 4)));
            }


            $('#phone').on('keypress', function(e) {
                if (!/[0-9]/.test(String.fromCharCode(e.which))) e.preventDefault();
            });
            $('#phone').on('input', function() {
                let val = $(this).val().replace(/\D/g, '');
                if (val.length > 10) val = val.slice(0, 10);
                $(this).val(val);
                if (val === '' || val.length === 10) clearError('phone', 'phone-error');
            });

            ['firstName', 'lastName', 'email', 'confirmPassword'].forEach(function(id) {
                $('#' + id).on('input', function() {
                    clearError(id, id + '-error');
                });
            });
            $('#terms').on('change', function() {
                if ($(this).is(':checked')) $('#terms-error').removeClass('show');
            });

            $('#registerForm').on('submit', function(e) {
                e.preventDefault();
                let valid = true;
                const fn = $('#firstName').val().trim();
                const ln = $('#lastName').val().trim();
                const email = $('#email').val().trim();
                const pw = $('#password').val();
                const cpw = $('#confirmPassword').val();
                const terms = $('#terms').is(':checked');

                if (!fn) {
                    showError('firstName', 'firstName-error', 'First name is required.');
                    valid = false;
                } else {
                    clearError('firstName', 'firstName-error');
                }

                if (!ln) {
                    showError('lastName', 'lastName-error', 'Last name is required.');
                    valid = false;
                } else {
                    clearError('lastName', 'lastName-error');
                }

                if (!email) {
                    showError('email', 'email-error', 'Email is required.');
                    valid = false;
                } else if (!validateEmail(email)) {
                    showError('email', 'email-error', 'Please enter a valid email address.');
                    valid = false;
                } else {
                    clearError('email', 'email-error');
                }

                const phone = $('#phone').val().trim();
                if (phone !== '' && phone.length !== 10) {
                    showError('phone', 'phone-error', 'Enter a valid 10-digit mobile number.');
                    valid = false;
                } else {
                    clearError('phone', 'phone-error');
                }

                if (!pw) {
                    showError('password', 'password-error', 'Password is required.');
                    valid = false;
                } else if (pw.length < 8) {
                    showError('password', 'password-error', 'Password must be at least 8 characters.');
                    valid = false;
                } else {
                    clearError('password', 'password-error');
                }

                if (!cpw) {
                    showError('confirmPassword', 'confirmPassword-error', 'Please confirm your password.');
                    valid = false;
                } else if (pw !== cpw) {
                    showError('confirmPassword', 'confirmPassword-error', 'Passwords do not match.');
                    valid = false;
                } else {
                    clearError('confirmPassword', 'confirmPassword-error');
                }

                if (!terms) {
                    $('#terms-error').addClass('show');
                    valid = false;
                } else {
                    $('#terms-error').removeClass('show');
                }

                if (valid) {
                    alert('Account created successfully!');
                }
            });
        });
    </script>

</body>

</html>