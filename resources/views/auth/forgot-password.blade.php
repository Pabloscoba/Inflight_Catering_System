<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Inflight Catering System') }} - Forgot Password</title>
    
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
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
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
    <div class="min-h-screen flex items-center justify-center aircraft-bg relative">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-overlay"></div>
        
        <!-- Forgot Password Container -->
        <div class="relative z-10 w-full max-w-md px-6 fade-in-up">
            <!-- Logo/Brand Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full logo-container mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Reset Password</h1>
                <p class="text-blue-100 text-sm">Enter your email to receive a password reset link.</p>
            </div>
            
            <!-- Forgot Password Card -->
            <div class="glass-card rounded-2xl p-8 shadow-2xl">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                        <p class="text-sm text-green-800 font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
                    </p>
                </div>
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus
                                class="input-field block w-full pl-10 pr-4 py-3 rounded-lg text-gray-900"
                                placeholder="Enter your email"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email Password Reset Link
                        </span>
                    </button>
                </form>
                
                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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
