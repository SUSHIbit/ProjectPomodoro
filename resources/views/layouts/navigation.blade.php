<nav x-data="{ open: false }" class="glass-effect border-b border-white/20 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-bold text-lg">Pomodoro Music</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white/80 hover:text-white transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-b-2 border-indigo-400 text-white' : '' }}">
                        Dashboard
                    </a>
                    
                    @auth
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.songs.index') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white/80 hover:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.songs.*') ? 'border-b-2 border-indigo-400 text-white' : '' }}">
                                Songs
                            </a>
                            
                            <a href="{{ route('admin.genres.index') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white/80 hover:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.genres.*') ? 'border-b-2 border-indigo-400 text-white' : '' }}">
                                Genres
                            </a>
                            
                            <a href="{{ route('admin.wallpapers.index') }}" 
                               class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-white/80 hover:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.wallpapers.*') ? 'border-b-2 border-indigo-400 text-white' : '' }}">
                                Wallpapers
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-white/10 hover:bg-white/20 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-lg">
                                <x-dropdown-link :href="route('profile.edit')" class="text-white hover:bg-white/20">
                                    Profile
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="text-white hover:bg-white/20">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-sm transition duration-150">Log in</a>
                        <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-sm rounded-lg transition duration-150">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/20 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-white/10 backdrop-blur-xl">
            <!-- Navigation Links -->
            <a href="{{ route('dashboard') }}" 
               class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : '' }}">
                Dashboard
            </a>
            
            @auth
                @if (Auth::user()->is_admin)
                    <a href="{{ route('admin.songs.index') }}" 
                       class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150 {{ request()->routeIs('admin.songs.*') ? 'bg-white/20 text-white' : '' }}">
                        Manage Songs
                    </a>
                    <a href="{{ route('admin.genres.index') }}" 
                       class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150 {{ request()->routeIs('admin.genres.*') ? 'bg-white/20 text-white' : '' }}">
                        Manage Genres
                    </a>
                    <a href="{{ route('admin.wallpapers.index') }}" 
                       class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150 {{ request()->routeIs('admin.wallpapers.*') ? 'bg-white/20 text-white' : '' }}">
                        Manage Wallpapers
                    </a>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-white/20 bg-white/10">
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-white/20 bg-white/10">
                <div class="mt-3 space-y-1">
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-white/80 hover:text-white hover:bg-white/20 transition duration-150">
                        Register
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>