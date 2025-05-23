<div class="absolute top-6 right-6 z-50" x-data="{ open: false }">
    @auth
        <div class="relative">
            <!-- Profile Circle -->
            <button @click="open = !open" 
                    class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-white/20 backdrop-blur-sm">
                <span class="text-white font-bold text-xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 @click.outside="open = false"
                 class="absolute right-0 mt-3 w-64 bg-white/10 backdrop-blur-2xl rounded-2xl shadow-2xl border border-white/20 py-2 overflow-hidden">
                
                <!-- User Info -->
                <div class="px-6 py-4 border-b border-white/10">
                    <div class="text-white font-semibold text-lg">{{ Auth::user()->name }}</div>
                    <div class="text-gray-300 text-sm">{{ Auth::user()->email }}</div>
                </div>
                
                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center px-6 py-3 text-white hover:bg-white/10 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile Settings
                    </a>
                    
                    @if (Auth::user()->is_admin)
                        <a href="{{ route('admin.songs.index') }}" 
                           class="flex items-center px-6 py-3 text-white hover:bg-white/10 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                            Manage Songs
                        </a>
                        <a href="{{ route('admin.genres.index') }}" 
                           class="flex items-center px-6 py-3 text-white hover:bg-white/10 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Manage Genres
                        </a>
                        <a href="{{ route('admin.wallpapers.index') }}" 
                           class="flex items-center px-6 py-3 text-white hover:bg-white/10 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Manage Wallpapers
                        </a>
                    @endif
                </div>
                
                <!-- Logout -->
                <div class="border-t border-white/10 pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-6 py-3 text-red-400 hover:bg-red-500/20 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Guest Login/Register Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('login') }}" 
               class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-xl text-white text-sm font-medium hover:bg-white/20 transition-all duration-300 border border-white/20">
                Log In
            </a>
            <a href="{{ route('register') }}" 
               class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 backdrop-blur-xl rounded-xl text-white text-sm font-medium hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 border border-white/20 shadow-lg">
                Register
            </a>
        </div>
    @endauth
</div>