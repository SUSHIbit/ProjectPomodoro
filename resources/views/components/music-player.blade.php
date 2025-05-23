<!-- Compact Music Player Component (Bottom Left) -->
<div class="fixed bottom-6 left-6 z-50 w-80">
    <div class="bg-white/10 backdrop-blur-2xl rounded-2xl p-4 shadow-2xl border border-white/20">
        <!-- Current Song Info -->
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                <template x-if="currentSong && currentWallpaper">
                    <img :src="currentWallpaper" :alt="currentSong.title" class="w-full h-full object-cover">
                </template>
                <template x-if="!currentWallpaper">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                    </svg>
                </template>
            </div>
            
            <div class="flex-1 min-w-0">
                <h3 class="text-white font-medium text-sm truncate" x-text="currentSong ? currentSong.title : 'No song selected'"></h3>
                <p class="text-gray-300 text-xs truncate" x-text="currentSong ? currentSong.genre : 'Select a song to play'"></p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-3">
            <div class="flex items-center justify-between text-xs text-gray-300 mb-1">
                <span x-text="currentPlaybackTime">0:00</span>
                <span x-text="totalDuration">0:00</span>
            </div>
            <div class="relative group cursor-pointer" @click="seekTo($event)">
                <div class="w-full bg-gray-700 rounded-full h-1.5">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-1.5 rounded-full transition-all duration-300 relative" 
                         :style="`width: ${progressPercent}%`">
                        <div class="absolute right-0 top-1/2 transform -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Buttons -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-2">
                <!-- Previous Button -->
                <button @click="playPrevSong()" 
                        class="group p-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white group-hover:text-indigo-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                    </svg>
                </button>

                <!-- Play/Pause Button -->
                <button @click="togglePlayPause()" 
                        class="group p-2 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 transform hover:scale-110 active:scale-95 shadow-lg hover:shadow-xl">
                    <svg x-show="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg x-show="isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>

                <!-- Next Button -->
                <button @click="playNextSong()" 
                        class="group p-2 rounded-full bg-white/10 hover:bg-white/20 transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white group-hover:text-indigo-300 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 18h2V6h-2v12zM6 18l8.5-6L6 6v12z"/>
                    </svg>
                </button>
            </div>

            <!-- Volume Control -->
            <div class="flex items-center space-x-2">
                <button @click="toggleMute()" class="p-1 rounded-lg hover:bg-white/10 transition-colors duration-300">
                    <svg x-show="!isMuted && volume > 0.5" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 7a2 2 0 00-2 2v6a2 2 0 002 2h4l4.667 3.5a.5.5 0 00.833-.4V3.9a.5.5 0 00-.833-.4L9 7H5z" />
                    </svg>
                    <svg x-show="!isMuted && volume <= 0.5 && volume > 0" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7a2 2 0 00-2 2v6a2 2 0 002 2h4l4.667 3.5a.5.5 0 00.833-.4V3.9a.5.5 0 00-.833-.4L9 7H5z" />
                    </svg>
                    <svg x-show="isMuted || volume === 0" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </button>
                <div class="w-16 relative group">
                    <input type="range" min="0" max="1" step="0.01" 
                           x-model="volume" 
                           @input="setVolume($event.target.value)"
                           class="w-full h-1.5 bg-gray-700 rounded-lg appearance-none cursor-pointer slider">
                </div>
            </div>
        </div>

        <!-- Song List Toggle -->
        <div x-data="{ showPlaylist: false }">
            <button @click="showPlaylist = !showPlaylist" 
                    class="w-full text-center text-white/70 hover:text-white text-xs py-2 border-t border-white/10 transition-colors duration-200">
                <span x-text="showPlaylist ? 'Hide Playlist' : 'Show Playlist'"></span>
            </button>
            
            <!-- Expandable Playlist -->
            <div x-show="showPlaylist" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="mt-3 max-h-48 overflow-y-auto space-y-1">
                <template x-for="song in allSongs" :key="song.id">
                    <div @click="playSong(song); showPlaylist = false" 
                         :class="currentSong && currentSong.id === song.id ? 'bg-indigo-600/30 border-indigo-400' : 'bg-white/5 hover:bg-white/10 border-transparent'" 
                         class="p-2 rounded-lg cursor-pointer transition-all duration-200 border text-xs">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-md overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <template x-if="song.wallpaper">
                                    <img :src="song.wallpaper" :alt="song.title" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!song.wallpaper">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium truncate" x-text="song.title"></p>
                                <p class="text-gray-400 truncate" x-text="song.genre"></p>
                            </div>
                            <div x-show="currentSong && currentSong.id === song.id && isPlaying" class="flex-shrink-0">
                                <div class="flex space-x-0.5">
                                    <div class="w-0.5 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
                                    <div class="w-0.5 h-3 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                                    <div class="w-0.5 h-3 bg-pink-400 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <!-- Hidden audio element -->
    <audio id="audioPlayer" class="hidden" preload="auto"></audio>
</div>