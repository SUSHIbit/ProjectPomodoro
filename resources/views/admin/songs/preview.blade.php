<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Song Preview') }} - {{ $song->title }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <a href="{{ route('admin.songs.index') }}" class="text-blue-500 hover:text-blue-700">
                        &larr; Back to Songs
                    </a>
                </div>

                <div class="flex flex-col md:flex-row gap-8">
                    <div class="w-full md:w-1/3">
                        <div class="rounded-lg overflow-hidden shadow-lg mb-4 bg-gray-100">
                            @if($song->wallpaper && $song->wallpaper->file_path)
                                <img src="{{ Storage::url($song->wallpaper->file_path) }}" alt="{{ $song->title }}" class="w-full h-auto">
                            @else
                                <div class="h-64 flex items-center justify-center text-gray-400">
                                    No wallpaper
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-full md:w-2/3">
                        <h2 class="text-2xl font-bold mb-2">{{ $song->title }}</h2>
                        
                        <div class="mb-4">
                            <span class="text-gray-600">Genre:</span> 
                            <span class="font-medium">{{ $song->genre ? $song->genre->name : 'None' }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <span class="text-gray-600">Wallpaper:</span> 
                            <span class="font-medium">{{ $song->wallpaper ? $song->wallpaper->name : 'None' }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">Audio Player</h3>
                            @if($song->file_path)
                                <audio controls class="w-full">
                                    <source src="{{ Storage::url($song->file_path) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <p class="text-red-500">No audio file available</p>
                            @endif
                        </div>
                        
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.songs.edit', $song) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Edit Song
                            </a>
                            <a href="{{ route('admin.songs.download', $song) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                Download MP3
                            </a>
                            <form action="{{ route('admin.songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this song?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Delete Song
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>