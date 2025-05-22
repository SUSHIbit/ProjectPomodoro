<!-- Pomodoro Timer Component -->
<div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
    <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-lg p-8 shadow-lg text-center">
        <h2 class="text-2xl font-semibold text-white mb-4" x-text="currentMode + ' Time'"></h2>
        
        <div class="text-6xl font-bold text-white mb-6">
            <span x-text="formatTime(minutes)"></span>:<span x-text="formatTime(seconds)"></span>
        </div>
        
        <div class="flex space-x-4 justify-center">
            <button @click="startTimer()" x-show="!isRunning" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                Start
            </button>
            <button @click="pauseTimer()" x-show="isRunning" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">
                Pause
            </button>
            <button @click="resetTimer()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
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