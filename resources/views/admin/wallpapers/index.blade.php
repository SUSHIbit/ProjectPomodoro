<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Wallpapers') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between mb-6">
                    <h3 class="text-lg font-semibold">Wallpapers List</h3>
                    <a href="{{ route('admin.wallpapers.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Add New Wallpaper
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($wallpapers as $wallpaper)
                        <div class="bg-white rounded-lg overflow-hidden shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if($wallpaper->file_path)
                                    <img src="{{ Storage::url($wallpaper->file_path) }}" alt="{{ $wallpaper->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-400">No Image</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-semibold">{{ $wallpaper->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Used in {{ $wallpaper->songs_count }} songs
                                </p>
                                <div class="flex justify-between mt-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.wallpapers.edit', $wallpaper) }}" class="text-blue-500 hover:text-blue-700">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.wallpapers.preview', $wallpaper) }}" class="text-green-500 hover:text-green-700">
                                            Preview
                                        </a>
                                    </div>
                                    <form action="{{ route('admin.wallpapers.destroy', $wallpaper) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wallpaper?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            No wallpapers found. Add your first wallpaper!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>