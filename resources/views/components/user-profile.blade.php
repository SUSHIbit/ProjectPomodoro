<!-- User Profile Component -->
<div class="absolute top-4 right-4 z-10" x-data="{ open: false }">
    @auth
        <div class="relative">
            <!-- Profile Circle -->
            <button @click="open = !open" class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-200 border-2 border-white border-opacity-30">
                <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.outside="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white bg-opacity-95 backdrop-blur-md rounded-lg shadow-lg border border-white border-opacity-30 py-1">
                
                <!-- User Info -->
                <div class="px-4 py-2 border-b border-gray-200 border-opacity-50">
                    <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                </div>
                
                <!-- Menu Items -->
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-50 transition-colors duration-150">
                    Profile Settings
                </a>
                
                @if (Auth::user()->is_admin)
                    <a href="{{ route('admin.songs.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-50 transition-colors duration-150">
                        Manage Songs
                    </a>
                    <a href="{{ route('admin.genres.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-50 transition-colors duration-150">
                        Manage Genres
                    </a>
                    <a href="{{ route('admin.wallpapers.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-50 transition-colors duration-150">
                        Manage Wallpapers
                    </a>
                @endif
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 border-opacity-50 mt-1">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-white hover:bg-opacity-50 transition-colors duration-150">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Guest Login/Register Buttons -->
        <div class="flex space-x-2">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-md rounded-lg text-white text-sm hover:bg-opacity-30 transition-all duration-200 border border-white border-opacity-30">
                Log In
            </a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-500 bg-opacity-80 backdrop-blur-md rounded-lg text-white text-sm hover:bg-opacity-90 transition-all duration-200 border border-blue-400 border-opacity-50">
                Register
            </a>
        </div>
    @endauth
</div>