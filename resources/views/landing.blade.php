<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Desai</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ URL::asset(asset_path('assets_front/icon/desai-fav.jpg')) }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            /* Vibrant red and blue gradient background */
            background: linear-gradient(-45deg, #ff2a2a, #ff7b54, #04A9F5, #005a9e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow-x: hidden;
            overflow-y: auto;
            margin: 0;
            padding: 0;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Bubbles animation */
        .bubbles-container {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }
        .bubble {
            position: absolute;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: float 10s infinite ease-in;
        }
        @keyframes float {
            0% {
                transform: translateY(0) scale(0.5);
                opacity: 0;
            }
            50% {
                opacity: 0.8;
            }
            100% {
                transform: translateY(-120vh) scale(1.2);
                opacity: 0;
            }
        }

        /* Generate multiple bubbles with different delays and sizes */
        .bubble:nth-child(1) { left: 10%; width: 40px; height: 40px; animation-duration: 8s; animation-delay: 0s; }
        .bubble:nth-child(2) { left: 20%; width: 80px; height: 80px; animation-duration: 10s; animation-delay: 2s; }
        .bubble:nth-child(3) { left: 35%; width: 50px; height: 50px; animation-duration: 7s; animation-delay: 4s; }
        .bubble:nth-child(4) { left: 50%; width: 60px; height: 60px; animation-duration: 11s; animation-delay: 0s; }
        .bubble:nth-child(5) { left: 65%; width: 30px; height: 30px; animation-duration: 6s; animation-delay: 3s; }
        .bubble:nth-child(6) { left: 80%; width: 70px; height: 70px; animation-duration: 12s; animation-delay: 1s; }
        .bubble:nth-child(7) { left: 90%; width: 45px; height: 45px; animation-duration: 9s; animation-delay: 5s; }
        .bubble:nth-child(8) { left: 5%; width: 25px; height: 25px; animation-duration: 14s; animation-delay: 6s; }
        .bubble:nth-child(9) { left: 75%; width: 55px; height: 55px; animation-duration: 11s; animation-delay: 3s; }
        .bubble:nth-child(10) { left: 45%; width: 35px; height: 35px; animation-duration: 9s; animation-delay: 7s; }

        /* Glassmorphism card - gives an icy/frosty look like a cold drink */
        .glass-card {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 255, 255, 0.6);
            transition: all 0.4s ease;
        }
        .glass-card:hover {
            transform: translateY(-12px) scale(1.03);
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        /* Specific Drink Themes aligned with Logo Colors */
        /* Admin: Desai Primary Blue */
        .card-cola:hover { border-color: #04A9F5; box-shadow: 0 15px 35px rgba(4, 169, 245, 0.4); }
        .card-cola .icon-bg { background: linear-gradient(135deg, #04A9F5, #007bb5); color: white; }
        .card-cola:hover h2 { color: #04A9F5; }
        
        /* Warehouse: Deep Blue */
        .card-lemon:hover { border-color: #0072ff; box-shadow: 0 15px 35px rgba(0, 114, 255, 0.4); }
        .card-lemon .icon-bg { background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; }
        .card-lemon:hover h2 { color: #0072ff; }
        
        /* Manufacturer: Cyan / Sky Blue */
        .card-orange:hover { border-color: #00d2ff; box-shadow: 0 15px 35px rgba(0, 210, 255, 0.4); }
        .card-orange .icon-bg { background: linear-gradient(135deg, #3a7bd5, #00d2ff); color: white; }
        .card-orange:hover h2 { color: #0072ff; }

        /* Driver / Sales Executive: Vibrant Coral / Desai Red */
        .card-driver:hover { border-color: #ff4b2b; box-shadow: 0 15px 35px rgba(255, 75, 43, 0.4); }
        .card-driver .icon-bg { background: linear-gradient(135deg, #ff416c, #ff4b2b); color: white; }
        .card-driver:hover h2 { color: #e11d48; }
        
    </style>
</head>
<body class="min-h-screen flex flex-col relative text-gray-800 px-4 py-8 sm:py-10 md:py-12">

    <!-- Bubbles Background -->
    <div class="bubbles-container">
        <div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div>
    </div>

    <main class="w-full max-w-7xl mx-auto my-auto flex flex-col items-center z-10">
        <div class="mb-6 sm:mb-10 md:mb-14 text-center drop-shadow-lg px-2 flex flex-col items-center">
            <!-- Desai Logo -->
            <img src="{{ URL::asset(asset_path('backend/assets/images/authentication/desai-delivery.png')) }}" alt="Desai Logo" class="h-16 sm:h-24 md:h-32 w-auto max-w-full object-contain mb-3 sm:mb-5 md:mb-6 drop-shadow-md">

            <h1 class="text-3xl sm:text-5xl md:text-6xl font-extrabold text-white mb-2 sm:mb-4 tracking-wide leading-tight" style="text-shadow: 2px 3px 6px rgba(0,0,0,0.3);">Welcome to Desai</h1>
            <p class="text-base sm:text-xl md:text-2xl text-white font-semibold drop-shadow-md">Select your portal to log in</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-7 w-full">
            
            <!-- Admin Login -->
            <a href="{{ url('/admin/login') }}" class="glass-card card-cola rounded-2xl sm:rounded-3xl p-6 sm:p-7 md:p-8 flex flex-col items-center justify-center text-center cursor-pointer group active:scale-95 h-full">
                <div class="icon-bg w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mb-4 sm:mb-5 shadow-xl transition-all duration-300 group-hover:rotate-12 group-hover:scale-110">
                    <i class="fas fa-user-shield text-2xl sm:text-3xl drop-shadow-md"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold mb-1.5 sm:mb-3 text-gray-800 transition-colors duration-300">Admin</h2>
                <p class="text-gray-800 font-medium text-xs sm:text-sm leading-relaxed">Manage your entire system backend and user controls.</p>
            </a>

            <!-- Warehouse Login -->
            <a href="{{ url('/warehouse/login') }}" class="glass-card card-lemon rounded-2xl sm:rounded-3xl p-6 sm:p-7 md:p-8 flex flex-col items-center justify-center text-center cursor-pointer group active:scale-95 h-full">
                <div class="icon-bg w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mb-4 sm:mb-5 shadow-xl transition-all duration-300 group-hover:-rotate-12 group-hover:scale-110">
                    <i class="fas fa-warehouse text-2xl sm:text-3xl drop-shadow-md"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold mb-1.5 sm:mb-3 text-gray-800 transition-colors duration-300">Warehouse</h2>
                <p class="text-gray-800 font-medium text-xs sm:text-sm leading-relaxed">Keep your inventory fresh, stocked, and flowing.</p>
            </a>

            <!-- Manufacturer Login -->
            <a href="{{ url('/manufacture/login') }}" class="glass-card card-orange rounded-2xl sm:rounded-3xl p-6 sm:p-7 md:p-8 flex flex-col items-center justify-center text-center cursor-pointer group active:scale-95 h-full">
                <div class="icon-bg w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mb-4 sm:mb-5 shadow-xl transition-all duration-300 group-hover:rotate-12 group-hover:scale-110">
                    <i class="fas fa-industry text-2xl sm:text-3xl drop-shadow-md"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold mb-1.5 sm:mb-3 text-gray-800 transition-colors duration-300">Manufacturer</h2>
                <p class="text-gray-800 font-medium text-xs sm:text-sm leading-relaxed">Brew up new products and track your manufacturing.</p>
            </a>

            <!-- Driver / Sales Executive -->
            <a href="#" class="glass-card card-driver rounded-2xl sm:rounded-3xl p-6 sm:p-7 md:p-8 flex flex-col items-center justify-center text-center cursor-pointer group active:scale-95 h-full w-full">
                <div class="icon-bg w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mb-4 sm:mb-5 shadow-xl transition-all duration-300 group-hover:-rotate-12 group-hover:scale-110 relative">
                    <i class="fas fa-truck-fast text-2xl sm:text-3xl drop-shadow-md"></i>
                    <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-white text-rose-600 flex items-center justify-center shadow-md text-xs">
                        <i class="fas fa-mobile-screen-button"></i>
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold mb-1.5 sm:mb-2 text-gray-800 transition-colors duration-300 leading-snug">Driver / Sales Executive</h2>
                <p class="text-gray-800 font-medium text-xs sm:text-sm leading-relaxed mb-3">Access deliveries, routes, attendance, and shop orders on the go.</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-white/60 text-rose-700 border border-rose-200 shadow-sm">
                    <i class="fas fa-mobile-alt"></i> Mobile App Access
                </span>
            </a>

        </div>

        <div class="mt-8 sm:mt-12 md:mt-16 text-white font-bold text-xs sm:text-sm text-center drop-shadow-md bg-black bg-opacity-20 px-5 sm:px-6 py-2 rounded-full">
            &copy; {{ date('Y') }} Desai System. All rights reserved.
        </div>
    </main>

</body>
</html>
