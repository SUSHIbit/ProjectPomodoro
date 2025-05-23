<div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
    <div class="bg-white/10 backdrop-blur-2xl rounded-3xl p-12 shadow-2xl border border-white/20 text-center max-w-lg">
        <!-- Timer Mode Indicator -->
        <div class="mb-6">
            <div class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-indigo-600/80 to-purple-600/80 rounded-full text-white text-sm font-medium backdrop-blur-sm border border-white/20">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-3 animate-pulse"></div>
                <span x-text="currentMode + ' Session'"></span>
            </div>
        </div>
        
        <!-- Timer Display -->
        <div class="relative mb-10">
            <div class="text-8xl font-bold text-white mb-2 font-mono tracking-wider drop-shadow-lg">
                <span x-text="formatTime(minutes)"></span>:<span x-text="formatTime(seconds)"></span>
            </div>
            <!-- Circular Progress Ring -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <svg class="w-72 h-72 transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/>
                    <circle cx="50" cy="50" r="45" 
                            stroke="url(#gradient)" 
                            stroke-width="3" 
                            fill="none"
                            stroke-linecap="round"
                            :stroke-dasharray="`${(progressPercent || 0) * 2.83} 283`"
                            class="transition-all duration-1000">
                    </circle>
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#6366f1"/>
                            <stop offset="100%" style="stop-color:#8b5cf6"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
        
        <!-- Control Buttons -->
        <div class="flex space-x-4 justify-center mb-8">
            <button @click="startTimer()" x-show="!isRunning" 
                    class="group px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <span>Start</span>
                </div>
            </button>
            
            <button @click="pauseTimer()" x-show="isRunning" 
                    class="group px-8 py-4 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                    <span>Pause</span>
                </div>
            </button>
            
            <button @click="resetTimer()" 
                    class="group px-8 py-4 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset</span>
                </div>
            </button>
        </div>
        
        <!-- Mode Selection -->
        <div class="flex justify-center space-x-3 mb-6">
            <button @click="setMode('Focus')" 
                    :class="{'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg': currentMode === 'Focus', 'bg-white/10 hover:bg-white/20': currentMode !== 'Focus'}" 
                    class="px-4 py-2 rounded-xl text-white text-sm font-medium transition-all duration-300 backdrop-blur-sm border border-white/20">
                Focus (25m)
            </button>
            <button @click="setMode('Short Break')" 
                    :class="{'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg': currentMode === 'Short Break', 'bg-white/10 hover:bg-white/20': currentMode !== 'Short Break'}" 
                    class="px-4 py-2 rounded-xl text-white text-sm font-medium transition-all duration-300 backdrop-blur-sm border border-white/20">
                Break (5m)
            </button>
            <button @click="setMode('Long Break')" 
                    :class="{'bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg': currentMode === 'Long Break', 'bg-white/10 hover:bg-white/20': currentMode !== 'Long Break'}" 
                    class="px-4 py-2 rounded-xl text-white text-sm font-medium transition-all duration-300 backdrop-blur-sm border border-white/20">
                Long (15m)
            </button>
        </div>
        
        <!-- Focus Sessions Counter -->
        <div class="flex items-center justify-center space-x-2 text-white/80">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span class="font-medium">Sessions Completed: </span>
            <span class="font-bold text-lg" x-text="focusCount"></span>
        </div>
    </div>
</div>