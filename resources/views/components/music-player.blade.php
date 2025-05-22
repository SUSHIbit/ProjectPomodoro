<!-- Music Player Component -->
<div class="absolute bottom-8 left-8 z-10 w-1/3 max-w-md">
    <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-lg p-6 shadow-lg">
        <h2 class="text-xl font-semibold text-white mb-4">Music Player</h2>
        
        <div class="mb-4">
            <div class="text-white mb-2">
                <span class="font-medium">Now Playing: </span>
                <span x-text="currentSong ? currentSong.title : 'No song selected'"></span>
            </div>
            
            <!-- Custom audio player layout -->
            <div class="bg-white bg-opacity-10 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <button @click="togglePlayPause()" class="text-white hover:text-gray-300">
                        <svg x-show="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    
                    <div class="flex-1 mx-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progressPercent}%`"></div>
                        </div>
                    </div>
                    
                    <div class="text-white text-xs">
                        <span x-text="currentPlaybackTime"></span> / <span x-text="totalDuration"></span>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button @click="playPrevSong()" class="text-white hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <button @click="playNextSong()" class="text-white hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Hidden audio element -->
            <audio id="audioPlayer" class="hidden" preload="auto"></audio>
        </div>
        
        <div class="max-h-60 overflow-y-auto">
            <h3 class="text-white text-sm font-medium mb-2">Song List</h3>
            @if(count($songs) > 0)
                <ul class="space-y-2" id="songList">
                    @foreach($songs as $index => $song)
                        <li>
                            <button 
                                @click="playSong({
                                    id: {{ $song->id }},
                                    title: '{{ addslashes($song->title) }}',
                                    file_path: '{{ Storage::url($song->file_path) }}',
                                    wallpaper: '{{ $song->wallpaper ? Storage::url($song->wallpaper->file_path) : '' }}',
                                    genre: '{{ $song->genre ? addslashes($song->genre->name) : 'Unknown' }}',
                                    index: {{ $index }}
                                })"
                                :class="{'bg-white bg-opacity-30': currentSong && currentSong.id == {{ $song->id }}}"
                                class="w-full text-left px-3 py-2 rounded bg-white bg-opacity-10 hover:bg-opacity-20 text-white transition-all duration-200"
                            >
                                <div class="font-medium">{{ $song->title }}</div>
                                <div class="text-xs text-gray-300">{{ $song->genre ? $song->genre->name : 'Unknown Genre' }}</div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-white text-center">No songs available</p>
            @endif
        </div>
    </div>
</div>