<!-- Premium Music Player Component -->
<div class="fixed bottom-0 left-0 right-0 z-50 bg-gradient-to-r from-slate-900/95 via-gray-900/95 to-slate-900/95 backdrop-blur-xl border-t border-white/10 shadow-2xl">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Song Info Section -->
            <div class="flex items-center space-x-4 flex-1 min-w-0">
                <div class="w-16 h-16 rounded-xl overflow-hidden shadow-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <template x-if="currentSong && currentWallpaper">
                        <img :src="currentWallpaper" :alt="currentSong.title" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!currentWallpaper">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </template>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-semibold text-lg truncate" x-text="currentSong ? currentSong.title : 'No song selected'"></h3>
                    <p class="text-gray-300 text-sm truncate" x-text="currentSong ? currentSong.genre : 'Select a song to play'"></p>
                </div>
            </div>

            <!-- Control Section -->
            <div class="flex items-center space-x-6 px-8">
                <!-- Previous Button -->
                <button @click="playPrevSong()" 
                        class="group p-3 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white group-hover:text-indigo-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                    </svg>
                </button>

                <!-- Play/Pause Button -->
                <button @click="togglePlayPause()" 
                        class="group p-4 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 transform hover:scale-110 active:scale-95 shadow-lg hover:shadow-xl">
                    <svg x-show="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg x-show="isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>

                <!-- Next Button -->
                <button @click="playNextSong()" 
                        class="group p-3 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white group-hover:text-indigo-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 18h2V6h-2v12zM6 18l8.5-6L6 6v12z"/>
                    </svg>
                </button>
            </div>

            <!-- Progress and Volume Section -->
            <div class="flex items-center space-x-4 flex-1 min-w-0 justify-end">
                <!-- Progress Bar -->
                <div class="flex items-center space-x-3 flex-1 max-w-md">
                    <span class="text-xs text-gray-400 font-mono w-10 text-right" x-text="currentPlaybackTime"></span>
                    <div class="flex-1 relative group">
                        <div class="w-full bg-gray-700 rounded-full h-2 cursor-pointer" @click="seekTo($event)">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-300 relative" 
                                 :style="`width: ${progressPercent}%`">
                                <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-4 h-4 bg-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 font-mono w-10" x-text="totalDuration"></span>
                </div>

                <!-- Volume Control -->
                <div class="flex items-center space-x-2">
                    <button @click="toggleMute()" class="p-2 rounded-lg hover:bg-white/10 transition-colors duration-300">
                        <svg x-show="!isMuted && volume > 0.5" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 7a2 2 0 00-2 2v6a2 2 0 002 2h4l4.667 3.5a.5.5 0 00.833-.4V3.9a.5.5 0 00-.833-.4L9 7H5z" />
                        </svg>
                        <svg x-show="!isMuted && volume <= 0.5 && volume > 0" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7a2 2 0 00-2 2v6a2 2 0 002 2h4l4.667 3.5a.5.5 0 00.833-.4V3.9a.5.5 0 00-.833-.4L9 7H5z" />
                        </svg>
                        <svg x-show="isMuted || volume === 0" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                        </svg>
                    </button>
                    <div class="w-20 relative group">
                        <input type="range" min="0" max="1" step="0.01" 
                               x-model="volume" 
                               @input="setVolume($event.target.value)"
                               class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer slider">
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden audio element -->
        <audio id="audioPlayer" class="hidden" preload="auto"></audio>
    </div>
</div>

<style>
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
</style>