<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pomodoro Music') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
                background-size: 400% 400%;
                animation: gradientShift 20s ease infinite;
            }
            
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
            @if (Route::has('login'))
                <div class="absolute top-6 right-6 z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-xl text-white font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20 shadow-lg">
                            Dashboard
                        </a>
                    @else
                        <div class="flex space-x-3">
                            <a href="{{ route('login') }}" 
                               class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-xl text-white font-medium hover:bg-white/20 transition-all duration-300 border border-white/20">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" 
                               class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 backdrop-blur-xl rounded-xl text-white font-semibold hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 border border-white/20 shadow-lg">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            @endif

            <div class="max-w-6xl mx-auto p-6 lg:p-8 text-center">
                <!-- Main Logo and Title -->
                <div class="flex justify-center mb-12">
                    <div class="flex items-center space-x-4 float-animation">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-6xl font-bold text-white drop-shadow-2xl">Pomodoro Music</h1>
                            <p class="text-xl text-white/80 mt-2">Boost your productivity with focused sessions and ambient music</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 mb-16">
                    <div class="glass-effect rounded-3xl p-8 shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-4">Smart Pomodoro Timer</h2>
                        <p class="text-white/80 text-lg leading-relaxed">
                            Enhance your focus with our intelligent timer. Automatically alternates between 25-minute work sessions and strategic breaks to maximize your productivity and prevent burnout.
                        </p>
                    </div>

                    <div class="glass-effect rounded-3xl p-8 shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-4">Premium Music Player</h2>
                        <p class="text-white/80 text-lg leading-relaxed">
                            Immerse yourself in carefully curated ambient music designed to enhance concentration. Our premium player features seamless controls and beautiful visualizations.
                        </p>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="glass-effect rounded-3xl p-12 shadow-2xl">
                    <h3 class="text-3xl font-bold text-white mb-6">Ready to Transform Your Productivity?</h3>
                    <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                        Join thousands of users who have already boosted their focus and productivity with our beautiful Pomodoro Music app.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('register') }}" 
                           class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Get Started Free
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-2xl transition-all duration-300 border border-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>