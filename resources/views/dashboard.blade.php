<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pomodoro Music') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative h-screen w-full overflow-hidden" 
             x-data="pomodoroApp()" 
             x-init="initApp()">
            
            <!-- Dynamic Background -->
            <div class="absolute inset-0 transition-all duration-1000 ease-in-out"
                 :style="currentWallpaper ? 
                     `background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('${currentWallpaper}'); background-size: cover; background-position: center;` : 
                     'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)'">
            </div>
            
            <!-- Animated Background Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/20 via-purple-900/20 to-pink-900/20"></div>
            
            <!-- Floating Particles Animation -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
                <div class="particle particle-4"></div>
                <div class="particle particle-5"></div>
            </div>
            
            <!-- App Logo (Top Left) -->
            <div class="absolute top-6 left-6 z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white drop-shadow-lg">Pomodoro Music</h1>
                </div>
            </div>
            
            <!-- User Profile Component -->
            @include('components.user-profile')
            
            <!-- Pomodoro Timer Component (Center and Bigger) -->
            @include('components.pomodoro-timer')
            
            <!-- Music Player Component (Bottom Left) -->
            @include('components.music-player')
        </div>
        
        <!-- Songs Data for JavaScript -->
        <script>
            window.pomodoroSongs = [
                @if(count($songs) > 0)
                    @foreach($songs as $index => $song)
                    {
                        id: {{ $song->id }},
                        title: "{{ addslashes($song->title) }}",
                        file_path: "{{ Storage::url($song->file_path) }}",
                        wallpaper: "{{ $song->wallpaper ? Storage::url($song->wallpaper->file_path) : '' }}",
                        genre: "{{ $song->genre ? addslashes($song->genre->name) : 'Unknown' }}",
                        index: {{ $index }}
                    }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                @endif
            ];
        </script>
        
        <!-- Pomodoro App Script -->
        @include('components.pomodoro-script')
        
        <!-- Custom Styles -->
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }
            
            @keyframes fadeInOut {
                0%, 100% { opacity: 0.3; }
                50% { opacity: 0.8; }
            }
            
            .particle {
                position: absolute;
                background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
                border-radius: 50%;
                pointer-events: none;
            }
            
            .particle-1 {
                width: 4px;
                height: 4px;
                top: 20%;
                left: 20%;
                animation: float 6s ease-in-out infinite, fadeInOut 4s ease-in-out infinite;
                animation-delay: 0s;
            }
            
            .particle-2 {
                width: 6px;
                height: 6px;
                top: 60%;
                left: 80%;
                animation: float 8s ease-in-out infinite, fadeInOut 3s ease-in-out infinite;
                animation-delay: 2s;
            }
            
            .particle-3 {
                width: 3px;
                height: 3px;
                top: 80%;
                left: 30%;
                animation: float 7s ease-in-out infinite, fadeInOut 5s ease-in-out infinite;
                animation-delay: 4s;
            }
            
            .particle-4 {
                width: 5px;
                height: 5px;
                top: 30%;
                left: 70%;
                animation: float 9s ease-in-out infinite, fadeInOut 6s ease-in-out infinite;
                animation-delay: 1s;
            }
            
            .particle-5 {
                width: 4px;
                height: 4px;
                top: 70%;
                left: 10%;
                animation: float 5s ease-in-out infinite, fadeInOut 4s ease-in-out infinite;
                animation-delay: 3s;
            }
            
            /* Custom slider styles */
            .slider::-webkit-slider-thumb {
                appearance: none;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                cursor: pointer;
                border: 2px solid white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            .slider::-moz-range-thumb {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                cursor: pointer;
                border: 2px solid white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }
            
            /* Smooth transitions for all interactive elements */
            * {
                transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 150ms;
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
            }
        </style>
    </body>
</html>