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
            timerProgressPercent: 0,
            
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
            volume: 0.7,
            isMuted: false,
            previousVolume: 0.7,
            
            // Initialize the app
            initApp() {
                console.log('Initializing premium Pomodoro Music app...');
                
                this.resetTimer();
                this.audioPlayer = document.getElementById('audioPlayer');
                this.loadSongs();
                this.setupAudioEvents();
                this.loadSettings();
                this.requestNotificationPermission();
            },
            
            // Load user settings from localStorage
            loadSettings() {
                try {
                    const savedVolume = localStorage.getItem('pomodoroVolume');
                    const savedMuted = localStorage.getItem('pomodoroMuted');
                    const savedFocusCount = localStorage.getItem('pomodoroFocusCount');
                    
                    if (savedVolume) {
                        this.volume = parseFloat(savedVolume);
                        this.previousVolume = this.volume;
                    }
                    
                    if (savedMuted) {
                        this.isMuted = JSON.parse(savedMuted);
                    }
                    
                    if (savedFocusCount) {
                        this.focusCount = parseInt(savedFocusCount);
                    }
                    
                    console.log('Settings loaded:', { volume: this.volume, muted: this.isMuted, focusCount: this.focusCount });
                } catch (error) {
                    console.warn('Could not load settings:', error);
                }
            },
            
            // Save user settings to localStorage
            saveSettings() {
                try {
                    localStorage.setItem('pomodoroVolume', this.volume.toString());
                    localStorage.setItem('pomodoroMuted', JSON.stringify(this.isMuted));
                    localStorage.setItem('pomodoroFocusCount', this.focusCount.toString());
                } catch (error) {
                    console.warn('Could not save settings:', error);
                }
            },
            
            // Request notification permission
            requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(permission => {
                        console.log('Notification permission:', permission);
                    });
                }
            },
            
            // Load songs from the server
            loadSongs() {
                if (window.pomodoroSongs && Array.isArray(window.pomodoroSongs)) {
                    this.allSongs = window.pomodoroSongs;
                    console.log('Songs loaded:', this.allSongs.length, 'songs');
                    
                    if (this.allSongs.length > 0) {
                        // Set first song as current but don't play it
                        this.currentSong = this.allSongs[0];
                        this.currentWallpaper = this.currentSong.wallpaper || '';
                        
                        if (this.audioPlayer) {
                            this.audioPlayer.src = this.currentSong.file_path;
                            this.audioPlayer.volume = this.isMuted ? 0 : this.volume;
                            this.audioPlayer.load();
                        }
                    }
                } else {
                    console.warn('No songs data found');
                    this.allSongs = [];
                }
            },
            
            // Setup audio player events
            setupAudioEvents() {
                if (!this.audioPlayer) {
                    console.error('Audio player not found');
                    return;
                }
                
                this.audioPlayer.addEventListener('play', () => {
                    this.isPlaying = true;
                    this.startPlaybackTracking();
                    console.log('Audio started playing');
                });
                
                this.audioPlayer.addEventListener('pause', () => {
                    this.isPlaying = false;
                    this.stopPlaybackTracking();
                    console.log('Audio paused');
                });
                
                this.audioPlayer.addEventListener('ended', () => {
                    console.log('Audio ended, playing next song');
                    this.playNextSong();
                });
                
                this.audioPlayer.addEventListener('loadedmetadata', () => {
                    this.updateTotalDuration();
                    console.log('Audio metadata loaded');
                });
                
                this.audioPlayer.addEventListener('timeupdate', () => {
                    this.updatePlaybackTime();
                });
                
                this.audioPlayer.addEventListener('error', (e) => {
                    console.error('Audio error:', e);
                    this.isPlaying = false;
                    this.stopPlaybackTracking();
                });
                
                this.audioPlayer.addEventListener('loadstart', () => {
                    console.log('Audio loading started');
                });
                
                this.audioPlayer.addEventListener('canplay', () => {
                    console.log('Audio can play');
                });
            },
            
            // Music Player Methods
            togglePlayPause() {
                if (!this.audioPlayer) {
                    console.error('Audio player not available');
                    return;
                }
                
                if (this.isPlaying) {
                    this.audioPlayer.pause();
                } else {
                    // If no song is selected, play the first one
                    if (!this.currentSong && this.allSongs.length > 0) {
                        this.playSong(this.allSongs[0]);
                        return;
                    }
                    
                    // Play current song
                    this.audioPlayer.play().catch(error => {
                        console.error('Error playing audio:', error);
                        this.isPlaying = false;
                    });
                }
            },
            
            playSong(song) {
                if (!song || !this.audioPlayer) {
                    console.error('Cannot play song:', song);
                    return;
                }
                
                console.log('Playing song:', song.title);
                
                this.currentSong = song;
                this.currentWallpaper = song.wallpaper || '';
                
                // Update audio source and settings
                this.audioPlayer.src = song.file_path;
                this.audioPlayer.volume = this.isMuted ? 0 : this.volume;
                this.audioPlayer.load();
                
                // Play the song
                this.audioPlayer.play().catch(error => {
                    console.error('Error playing song:', error);
                    this.isPlaying = false;
                });
            },
            
            playNextSong() {
                if (this.allSongs.length === 0) {
                    console.warn('No songs available');
                    return;
                }
                
                if (!this.currentSong) {
                    this.playSong(this.allSongs[0]);
                    return;
                }
                
                const currentIndex = this.allSongs.findIndex(s => s.id === this.currentSong.id);
                const nextIndex = (currentIndex + 1) % this.allSongs.length;
                this.playSong(this.allSongs[nextIndex]);
            },
            
            playPrevSong() {
                if (this.allSongs.length === 0) {
                    console.warn('No songs available');
                    return;
                }
                
                if (!this.currentSong) {
                    this.playSong(this.allSongs[this.allSongs.length - 1]);
                    return;
                }
                
                const currentIndex = this.allSongs.findIndex(s => s.id === this.currentSong.id);
                const prevIndex = (currentIndex - 1 + this.allSongs.length) % this.allSongs.length;
                this.playSong(this.allSongs[prevIndex]);
            },
            
            // Volume Controls
            setVolume(value) {
                const newVolume = parseFloat(value);
                this.volume = Math.max(0, Math.min(1, newVolume)); // Clamp between 0 and 1
                
                if (this.audioPlayer) {
                    this.audioPlayer.volume = this.isMuted ? 0 : this.volume;
                }
                
                // If volume is set to 0, consider it muted
                if (this.volume === 0) {
                    this.isMuted = true;
                } else if (this.isMuted && this.volume > 0) {
                    this.isMuted = false;
                }
                
                this.saveSettings();
                console.log('Volume set to:', this.volume);
            },
            
            toggleMute() {
                if (this.isMuted) {
                    // Unmute
                    this.isMuted = false;
                    this.volume = this.previousVolume > 0 ? this.previousVolume : 0.7;
                } else {
                    // Mute
                    this.isMuted = true;
                    this.previousVolume = this.volume;
                    this.volume = 0;
                }
                
                if (this.audioPlayer) {
                    this.audioPlayer.volume = this.isMuted ? 0 : this.volume;
                }
                
                this.saveSettings();
                console.log('Mute toggled:', this.isMuted ? 'ON' : 'OFF');
            },
            
            // Seek functionality
            seekTo(event) {
                if (!this.audioPlayer || !this.audioPlayer.duration || isNaN(this.audioPlayer.duration)) {
                    return;
                }
                
                const rect = event.currentTarget.getBoundingClientRect();
                const percent = Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
                const seekTime = percent * this.audioPlayer.duration;
                
                this.audioPlayer.currentTime = seekTime;
                console.log('Seeked to:', seekTime, 'seconds');
            },
            
            // Playback tracking
            startPlaybackTracking() {
                this.stopPlaybackTracking();
                this.updateInterval = setInterval(() => {
                    this.updatePlaybackTime();
                }, 1000);
            },
            
            stopPlaybackTracking() {
                if (this.updateInterval) {
                    clearInterval(this.updateInterval);
                    this.updateInterval = null;
                }
            },
            
            updatePlaybackTime() {
                if (!this.audioPlayer) return;
                
                const currentTime = this.audioPlayer.currentTime || 0;
                const duration = this.audioPlayer.duration || 1;
                
                // Update current time display
                const minutes = Math.floor(currentTime / 60);
                const seconds = Math.floor(currentTime % 60);
                this.currentPlaybackTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                // Update progress percentage
                this.progressPercent = (currentTime / duration) * 100;
            },
            
            updateTotalDuration() {
                if (this.audioPlayer && this.audioPlayer.duration && !isNaN(this.audioPlayer.duration)) {
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
                    console.log('Timer started');
                }
            },
            
            pauseTimer() {
                this.isRunning = false;
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
                console.log('Timer paused');
            },
            
            resetTimer() {
                if (this.isRunning) {
                    this.pauseTimer();
                }
                this.setMode(this.currentMode);
                this.updateTimerProgress();
                console.log('Timer reset');
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
                this.updateTimerProgress();
            },
            
            updateTimerProgress() {
                const totalSeconds = this.getTotalSecondsForMode(this.currentMode);
                const remainingSeconds = (this.minutes * 60) + this.seconds;
                this.timerProgressPercent = ((totalSeconds - remainingSeconds) / totalSeconds) * 100;
            },
            
            getTotalSecondsForMode(mode) {
                switch(mode) {
                    case 'Focus': return 25 * 60;
                    case 'Short Break': return 5 * 60;
                    case 'Long Break': return 15 * 60;
                    default: return 25 * 60;
                }
            },
            
            timerComplete() {
                this.pauseTimer();
                console.log('Timer completed for mode:', this.currentMode);
                
                // Play notification sound
                this.playNotificationSound();
                
                // Show browser notification
                this.showBrowserNotification();
                
                // Switch mode and update focus count
                const previousMode = this.currentMode;
                if (this.currentMode === 'Focus') {
                    this.focusCount++;
                    this.setMode(this.focusCount % 4 === 0 ? 'Long Break' : 'Short Break');
                } else {
                    this.setMode('Focus');
                }
                
                // Save progress
                this.saveSettings();
                
                // Auto-start next session after 3 seconds
                setTimeout(() => {
                    if (!this.isRunning) { // Only start if user hasn't manually started
                        this.startTimer();
                    }
                }, 3000);
                
                console.log(`Switched from ${previousMode} to ${this.currentMode}`);
            },
            
            playNotificationSound() {
                try {
                    // Create audio context for notification beep
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) {
                        console.warn('Web Audio API not supported');
                        return;
                    }
                    
                    const audioContext = new AudioContext();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    // Create a pleasant notification sound
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);
                    
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.5);
                    
                    console.log('Notification sound played');
                } catch (error) {
                    console.warn('Could not play notification sound:', error);
                }
            },
            
            showBrowserNotification() {
                if ('Notification' in window && Notification.permission === 'granted') {
                    const notification = new Notification('Pomodoro Timer', {
                        body: `${this.currentMode} session completed! 🎉`,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                        tag: 'pomodoro-timer',
                        requireInteraction: false,
                        actions: [
                            {
                                action: 'start',
                                title: 'Start Next Session'
                            }
                        ]
                    });
                    
                    // Auto-close notification after 5 seconds
                    setTimeout(() => {
                        notification.close();
                    }, 5000);
                    
                    console.log('Browser notification shown');
                } else {
                    console.warn('Notifications not available or not permitted');
                }
            },
            
            setMode(mode) {
                const validModes = ['Focus', 'Short Break', 'Long Break'];
                if (!validModes.includes(mode)) {
                    console.error('Invalid mode:', mode);
                    return;
                }
                
                this.currentMode = mode;
                
                // Set timer duration based on mode
                switch (mode) {
                    case 'Focus':
                        this.minutes = 25;
                        break;
                    case 'Short Break':
                        this.minutes = 5;
                        break;
                    case 'Long Break':
                        this.minutes = 15;
                        break;
                }
                
                this.seconds = 0;
                this.updateTimerProgress();
                
                console.log('Mode set to:', mode);
            },
            
            formatTime(time) {
                return Math.max(0, Math.floor(time)).toString().padStart(2, '0');
            },
            
            // Utility methods
            getProgressPercent() {
                return this.timerProgressPercent;
            },
            
            getCurrentSongTitle() {
                return this.currentSong ? this.currentSong.title : 'No song selected';
            },
            
            getCurrentSongGenre() {
                return this.currentSong ? (this.currentSong.genre || 'Unknown') : 'Select a song';
            },
            
            // Cleanup method
            destroy() {
                this.pauseTimer();
                this.stopPlaybackTracking();
                
                if (this.audioPlayer) {
                    this.audioPlayer.pause();
                    this.audioPlayer.src = '';
                }
                
                this.saveSettings();
                console.log('Pomodoro app destroyed');
            }
        }));
    });
    
    // Global event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Request notification permission on load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                console.log('Notification permission granted:', permission === 'granted');
            });
        }
        
        // Handle visibility change to pause/resume timer
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log('Page hidden');
            } else {
                console.log('Page visible');
            }
        });
        
        // Handle before unload to save settings
        window.addEventListener('beforeunload', function() {
            // Settings are automatically saved throughout the app
            console.log('Page unloading');
        });
        
        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // Only handle shortcuts when not in an input field
            if (event.target.tagName.toLowerCase() === 'input' || 
                event.target.tagName.toLowerCase() === 'textarea') {
                return;
            }
            
            // Space bar to toggle play/pause
            if (event.code === 'Space') {
                event.preventDefault();
                const app = Alpine.$data(document.querySelector('[x-data="pomodoroApp()"]'));
                if (app) {
                    app.togglePlayPause();
                }
            }
            
            // Arrow keys for previous/next song
            if (event.code === 'ArrowLeft') {
                event.preventDefault();
                const app = Alpine.$data(document.querySelector('[x-data="pomodoroApp()"]'));
                if (app) {
                    app.playPrevSong();
                }
            }
            
            if (event.code === 'ArrowRight') {
                event.preventDefault();
                const app = Alpine.$data(document.querySelector('[x-data="pomodoroApp()"]'));
                if (app) {
                    app.playNextSong();
                }
            }
        });
    });
    
    // Service Worker registration for offline support (optional)
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            // Uncomment if you want to add offline support
            // navigator.serviceWorker.register('/sw.js').then(function(registration) {
            //     console.log('ServiceWorker registration successful');
            // }).catch(function(err) {
            //     console.log('ServiceWorker registration failed');
            // });
        });
    }
</script>