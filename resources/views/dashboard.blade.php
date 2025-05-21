<x-app-layout>
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
        
        <!-- Pomodoro Timer (Center) -->
        <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
            <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-lg p-8 shadow-lg text-center">
                <h2 class="text-2xl font-semibold text-white mb-4" x-text="currentMode + ' Time'"></h2>
                
                <div class="text-6xl font-bold text-white mb-6">
                    <span x-text="formatTime(minutes)"></span>:<span x-text="formatTime(seconds)"></span>
                </div>
                
                <div class="flex space-x-4 justify-center">
                    <button @click="startTimer" x-show="!isRunning" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                        Start
                    </button>
                    <button @click="pauseTimer" x-show="isRunning" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                        Pause
                    </button>
                    <button @click="resetTimer" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                        Reset
                    </button>
                </div>
                
                <div class="mt-6 flex justify-center space-x-3">
                    <button @click="setMode('Focus')" :class="{'bg-blue-600': currentMode === 'Focus', 'bg-blue-400': currentMode !== 'Focus'}" class="px-3 py-1 rounded text-white text-sm">
                        Focus (25m)
                    </button>
                    <button @click="setMode('Short Break')" :class="{'bg-blue-600': currentMode === 'Short Break', 'bg-blue-400': currentMode !== 'Short Break'}" class="px-3 py-1 rounded text-white text-sm">
                        Short Break (5m)
                    </button>
                    <button @click="setMode('Long Break')" :class="{'bg-blue-600': currentMode === 'Long Break', 'bg-blue-400': currentMode !== 'Long Break'}" class="px-3 py-1 rounded text-white text-sm">
                        Long Break (15m)
                    </button>
                </div>
                
                <div class="mt-4 text-white">
                    <span>Focus Sessions: </span>
                    <span x-text="focusCount"></span>
                </div>
            </div>
        </div>
        
        <!-- Music Player (Bottom Left) -->
        <div class="absolute bottom-8 left-8 z-10 w-1/3 max-w-md">
            <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-lg p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-white mb-4">Music Player</h2>
                
                @auth
                    <div class="mb-4">
                        <div class="text-white mb-2">
                            <span class="font-medium">Now Playing: </span>
                            <span x-text="currentSong ? currentSong.title : 'No song selected'"></span>
                        </div>
                        
                        <audio id="audioPlayer" class="w-full" controls @ended="songEnded">
                            <source src="" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    
                    <div class="max-h-60 overflow-y-auto">
                        <h3 class="text-white text-sm font-medium mb-2">Song List</h3>
                        <ul class="space-y-2">
                            @foreach($songs as $song)
                                <li>
                                    <button 
                                        @click="playSong({{ json_encode([
                                            'id' => $song->id,
                                            'title' => $song->title,
                                            'file_path' => Storage::url($song->file_path),
                                            'wallpaper' => $song->wallpaper ? Storage::url($song->wallpaper->file_path) : null,
                                            'genre' => $song->genre ? $song->genre->name : 'Unknown'
                                        ]) }})"
                                        class="w-full text-left px-3 py-2 rounded bg-white bg-opacity-10 hover:bg-opacity-20 text-white"
                                    >
                                        <div class="font-medium">{{ $song->title }}</div>
                                        <div class="text-xs text-gray-300">{{ $song->genre ? $song->genre->name : 'Unknown Genre' }}</div>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="text-white text-center py-4">
                        <p class="mb-4">Please log in to access the music player</p>
                        <a href="{{ route('login') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Log In
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Login Prompt Modal (for guests) -->
        <div x-show="showLoginModal" 
             class="fixed inset-0 z-50 flex items-center justify-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black bg-opacity-50" @click="showLoginModal = false"></div>
            <div class="relative bg-white rounded-lg p-8 max-w-md w-full mx-4 z-10">
                <h2 class="text-2xl font-bold mb-4">Login Required</h2>
                <p class="mb-6">Please log in to use the Pomodoro timer and music player.</p>
                <div class="flex justify-end">
                    <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mr-3">
                        Log In
                    </a>
                    <button @click="showLoginModal = false" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-md">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alpine.js App Script -->
    @push('scripts')
    <script>
        function pomodoroApp() {
            return {
                // Timer state
                minutes: 25,
                seconds: 0,
                isRunning: false,
                timer: null,
                currentMode: 'Focus',
                focusCount: 0,
                
                // Modal state
                showLoginModal: false,
                
                // Music state
                currentSong: null,
                currentWallpaper: null,
                
                // Initialize the app
                initApp() {
                    this.resetTimer();
                    
                    // Show login modal for guests when interacting with protected features
                    @guest
                    this.$watch('isRunning', (value) => {
                        if (value) {
                            this.isRunning = false;
                            this.showLoginModal = true;
                        }
                    });
                    @endguest
                },
                
                // Timer functions
                startTimer() {
                    @auth
                    if (!this.isRunning) {
                        this.isRunning = true;
                        this.timer = setInterval(() => {
                            this.updateTimer();
                        }, 1000);
                    }
                    @else
                    this.showLoginModal = true;
                    @endauth
                },
                
                pauseTimer() {
                    this.isRunning = false;
                    clearInterval(this.timer);
                },
                
                resetTimer() {
                    this.pauseTimer();
                    this.setMode(this.currentMode);
                },
                
                updateTimer() {
                    if (this.seconds > 0) {
                        this.seconds--;
                    } else if (this.minutes > 0) {
                        this.minutes--;
                        this.seconds = 59;
                    } else {
                        this.timerComplete();
                    }
                },
                
                timerComplete() {
                    this.pauseTimer();
                    
                    // Play notification sound
                    const notification = new Audio('/notification.mp3');
                    notification.play();
                    
                    // Switch to next mode
                    if (this.currentMode === 'Focus') {
                        this.focusCount++;
                        if (this.focusCount % 4 === 0) {
                            this.setMode('Long Break');
                        } else {
                            this.setMode('Short Break');
                        }
                    } else {
                        this.setMode('Focus');
                    }
                    
                    // Auto-start next timer
                    this.startTimer();
                },
                
                setMode(mode) {
                    this.currentMode = mode;
                    
                    if (mode === 'Focus') {
                        this.minutes = 25;
                    } else if (mode === 'Short Break') {
                        this.minutes = 5;
                    } else if (mode === 'Long Break') {
                        this.minutes = 15;
                    }
                    
                    this.seconds = 0;
                },
                
                formatTime(time) {
                    return time.toString().padStart(2, '0');
                },
                
                // Music functions
                playSong(song) {
                    @auth
                    this.currentSong = song;
                    const audioPlayer = document.getElementById('audioPlayer');
                    audioPlayer.src = song.file_path;
                    audioPlayer.play();
                    
                    // Update wallpaper if available
                    if (song.wallpaper) {
                        this.currentWallpaper = song.wallpaper;
                    }
                    @else
                    this.showLoginModal = true;
                    @endauth
                },
                
                songEnded() {
                    // Logic to auto-play next song could be added here
                }
            };
        }
    </script>
    @endpush
</x-app-layout>