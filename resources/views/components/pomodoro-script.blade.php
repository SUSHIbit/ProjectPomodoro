<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pomodoroApp', () => ({
            // Timer state
            minutes: 25,
            seconds: 0,
            isRunning: false,
            timer: null,
            currentMode: 'Focus',
            focusCount: 0,
            
            // Music state
            currentSong: null,
            currentWallpaper: null,
            audioPlayer: null,
            isPlaying: false,
            allSongs: [],
            currentPlaybackTime: '0:00',
            totalDuration: '0:00',
            progressPercent: 0,
            updateInterval: null,
            
            // Initialize the app
            initApp() {
                console.log('Initializing app...');
                
                this.resetTimer();
                this.audioPlayer = document.getElementById('audioPlayer');
                this.loadSongs();
                this.setupAudioEvents();
            },
            
            // Load songs from the server
            loadSongs() {
                // This will be populated by the dashboard view
                if (window.pomodoroSongs) {
                    this.allSongs = window.pomodoroSongs;
                    console.log('Loaded songs:', this.allSongs);
                    
                    if (this.allSongs.length > 0) {
                        this.currentSong = this.allSongs[0];
                        this.currentWallpaper = this.currentSong.wallpaper || '';
                        
                        if (this.audioPlayer) {
                            this.audioPlayer.src = this.currentSong.file_path;
                            this.audioPlayer.load();
                        }
                    }
                }
            },
            
            // Setup audio player events
            setupAudioEvents() {
                if (this.audioPlayer) {
                    this.audioPlayer.addEventListener('play', () => {
                        this.isPlaying = true;
                        this.startPlaybackTracking();
                    });
                    
                    this.audioPlayer.addEventListener('pause', () => {
                        this.isPlaying = false;
                        this.stopPlaybackTracking();
                    });
                    
                    this.audioPlayer.addEventListener('ended', () => {
                        this.playNextSong();
                    });
                    
                    this.audioPlayer.addEventListener('loadedmetadata', () => {
                        this.updateTotalDuration();
                    });
                    
                    this.audioPlayer.addEventListener('timeupdate', () => {
                        this.updatePlaybackTime();
                    });
                    
                    this.audioPlayer.addEventListener('error', (e) => {
                        console.error('Audio error:', e);
                    });
                }
            },
            
            // Music Player Methods
            togglePlayPause() {
                if (!this.audioPlayer) return;
                
                if (this.isPlaying) {
                    this.audioPlayer.pause();
                } else {
                    if (!this.currentSong && this.allSongs.length > 0) {
                        this.playSong(this.allSongs[0]);
                        return;
                    }
                    this.audioPlayer.play().catch(console.error);
                }
            },
            
            playSong(song) {
                this.currentSong = song;
                this.currentWallpaper = song.wallpaper || '';
                
                if (this.audioPlayer) {
                    this.audioPlayer.src = song.file_path;
                    this.audioPlayer.load();
                    this.audioPlayer.play().catch(console.error);
                }
            },
            
            playNextSong() {
                if (this.allSongs.length === 0 || !this.currentSong) return;
                
                const currentIndex = this.allSongs.findIndex(s => s.id === this.currentSong.id);
                const nextIndex = (currentIndex + 1) % this.allSongs.length;
                this.playSong(this.allSongs[nextIndex]);
            },
            
            playPrevSong() {
                if (this.allSongs.length === 0 || !this.currentSong) return;
                
                const currentIndex = this.allSongs.findIndex(s => s.id === this.currentSong.id);
                const prevIndex = (currentIndex - 1 + this.allSongs.length) % this.allSongs.length;
                this.playSong(this.allSongs[prevIndex]);
            },
            
            // Playback tracking
            startPlaybackTracking() {
                this.stopPlaybackTracking();
                this.updateInterval = setInterval(() => this.updatePlaybackTime(), 1000);
            },
            
            stopPlaybackTracking() {
                if (this.updateInterval) {
                    clearInterval(this.updateInterval);
                    this.updateInterval = null;
                }
            },
            
            updatePlaybackTime() {
                if (this.audioPlayer) {
                    const currentTime = this.audioPlayer.currentTime || 0;
                    const duration = this.audioPlayer.duration || 1;
                    
                    const minutes = Math.floor(currentTime / 60);
                    const seconds = Math.floor(currentTime % 60);
                    this.currentPlaybackTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    this.progressPercent = (currentTime / duration) * 100;
                }
            },
            
            updateTotalDuration() {
                if (this.audioPlayer && !isNaN(this.audioPlayer.duration)) {
                    const duration = this.audioPlayer.duration;
                    const minutes = Math.floor(duration / 60);
                    const seconds = Math.floor(duration % 60);
                    this.totalDuration = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
            },
            
            // Timer Methods
            startTimer() {
                if (!this.isRunning) {
                    this.isRunning = true;
                    this.timer = setInterval(() => this.updateTimer(), 1000);
                }
            },
            
            pauseTimer() {
                this.isRunning = false;
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },
            
            resetTimer() {
                if (this.isRunning) this.pauseTimer();
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
                
                // Play notification
                try {
                    const notification = new Audio('/notification.mp3');
                    notification.play().catch(console.error);
                } catch (error) {
                    console.error('Notification error:', error);
                }
                
                // Browser notification
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Pomodoro Timer', {
                        body: `${this.currentMode} session completed!`,
                        icon: '/favicon.ico'
                    });
                }
                
                // Switch mode
                if (this.currentMode === 'Focus') {
                    this.focusCount++;
                    this.setMode(this.focusCount % 4 === 0 ? 'Long Break' : 'Short Break');
                } else {
                    this.setMode('Focus');
                }
                
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
            }
        }));
    });
    
    // Request notification permission
    document.addEventListener('DOMContentLoaded', function() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    });
    </script>