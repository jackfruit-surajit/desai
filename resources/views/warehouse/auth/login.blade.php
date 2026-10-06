@extends('warehouse.layouts.AuthLayout')

@section('title', 'Login')

@section('content')
   

    <style>
    /* ── Video Background ── */
    .video-bg-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
    }

    .video-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
        object-fit: cover;
    }

    /* Dark overlay for readability */
    .video-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
    }

    /* ── Auth Form sits above video ── */
    .auth-form {
        position: relative;
        z-index: 1;
    }

    /* ── Glassmorphism card ── */
    .auth-form .card {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
    }

    /* ── Text contrast on glass card ── */
    .auth-form .card h4,
    .auth-form .card label {
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0,0,0,0.4);
    }

    /* ── Input fields ── */
    .auth-form .form-control {
            padding: 12px 25px;
            background: transparent;
            border: 0;
            color: #EE1A28 !important;
            border-radius: 0;
            border-bottom: 1px solid #ffffff;
    }
    
    .auth-form .form-control::placeholder {
        color: #fff !important;
    }
    .auth-form .form-control:hover{
        transition: 0.4s;
        transform: scale(1.03);
        background-color: transparent;
        border-bottom: 1.5px solid #EE1A28;
    }


    .auth-form .form-control:focus {
         background-color: transparent;
        border-bottom: 1.5px solid #EE1A28;
        box-shadow: none;
    }

    /* ── Ensure body/page background is transparent to show video ── */
    body,
    .auth-wrapper,
    .auth-bg,
    .pc-container,
    .auth-main {
        background: transparent !important;
    }
    
    .auth-sidefooter{
        display:none;
    }
    .auth-form .card h4{
        font-size: 32px;
        padding: 0 0 15px 0;
    }
    .auth-form .log-btn{
        background-color: #EE1A28;
        border-color: #EE1A28;
        padding: 10px 25px;
        font-size: 20px;
        letter-spacing: 1.9px;
    }
    .auth-form .log-btn:hover{
        transition: 0.4s;
        background-color: #2d4b3a;
        border-color: #2d4b3a;
    }
    .lock-login{
        width: 30%;
        margin: 3% 0 6%;
        background-color: #fff;
        border-radius: 8px;
        padding: 10px;
    }
   </style>
   
   
    <!---- Video Background ---->
    <div class="video-bg-wrapper">
        <video autoplay muted loop playsinline class="video-bg">
            <source src="/public/backend/assets/images/authentication/delivery-video.mp4" type="video/mp4">
        </video>
        <div class="video-bg-overlay"></div>
    </div>

    <div class="auth-form">
        <div class="card my-5">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ URL::asset(asset_path('backend/assets/images/authentication/desai-delivery.png')) }}" alt="images" class="lock-login">
                    <h4 class="f-w-500 mb-1">Login with your email</h4>
                </div>
                <form method="POST" action="{{ route('warehouse-login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="email" class="form-control" name="email" value=""   placeholder="Email Address">
                        @if ($errors->has('email'))
                          <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" value="" name="password"  placeholder="Password">
                        
                        @if ($errors->has('password'))
                          <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    
                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary log-btn">Login</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
@endsection
