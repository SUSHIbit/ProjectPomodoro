<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pomodoro Music') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative h-screen w-full overflow-hidden" 
             x-data="pomodoroApp()" 
             x-init="initApp()"
             :style="currentWallpaper ? `background-image: url('${currentWallpaper}'); background-size: cover; background-position: center;` : ''">
            
            <!-- Overlay for better text readability -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            
            <!-- App Logo (Top Left) -->
            <div class="absolute top-4 left-4 z-10">
                <h1 class="text-2xl font-bold text-white">Pomodoro Music</h1>
            </div>
            
            <!-- User Profile Component -->
            @include('components.user-profile')
            
            <!-- Pomodoro Timer Component -->
            @include('components.pomodoro-timer')
            
            <!-- Music Player Component -->
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
    </body>
</html>