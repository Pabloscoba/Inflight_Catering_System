<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Inflight Catering System') }} - Register</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-overlay {
            background: linear-gradient(135deg, rgba(0, 31, 63, 0.5) 0%, rgba(13, 71, 161, 0.4) 50%, rgba(118, 156, 185, 0.5) 100%);
            backdrop-filter: blur(5px);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(13, 71, 161, 0.2);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 1);
            border-color: rgba(13, 71, 161, 0.8);
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
            outline: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 71, 161, 0.4);
        }
        
        .logo-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 15px;
        }
        
        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .aircraft-bg {
            background-image: url('/images/images-bg.jpg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center aircraft-bg relative py-12">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-overlay"></div>
        
        <!-- Register Container -->
        <div class="relative z-10 w-full max-w-md px-6 fade-in-up">
            <!-- Logo/Brand Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl logo-container mb-4">
                    <img src="{{ asset('images/ATCL LOGO.jpg') }}" alt="Air Tanzania Logo" class="logo-img">
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
                <p class="text-blue-100 text-sm">Contact your administrator for registration</p>
            </div>
            
            <!-- Register Card -->
            <div class="glass-card rounded-2xl p-8 shadow-2xl">
                <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-900 mb-1">Registration Restricted</h3>
                            <p class="text-xs text-blue-700">
                                New user accounts can only be created by system administrators. Please contact your IT department or system administrator to request an account.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Email Support</p>
                            <p class="text-sm font-medium text-gray-900">admin@inflightcatering.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-500">Phone Support</p>
                            <p class="text-sm font-medium text-gray-900">+255 674 066 390</p>
                        </div>
                    </div>
                </div>
                
                <!-- Back to Login -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn-primary inline-flex items-center justify-center w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-blue-100">
                    &copy; {{ date('Y') }} Inflight Catering System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
