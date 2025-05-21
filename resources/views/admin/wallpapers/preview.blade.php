<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallpaper Preview') }} - {{ $wallpaper->name }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-4">
                    <a href="{{ route('admin.wallpapers.index') }}" class="text-blue-500 hover:text-blue-700">
                        &larr; Back to Wallpapers
                    </a>
                </div>

                <div class="flex flex-col items-center">
                    <h3 class="text-xl font-semibold mb-4">{{ $wallpaper->name }}</h3>
                    
                    <div class="w-full max-w-3xl rounded-lg overflow-hidden shadow-lg mb-6">
                        @if($wallpaper->file_path)
                            <img src="{{ Storage::url($wallpaper->file_path) }}" alt="{{ $wallpaper->name }}" class="w-full h-auto">
                        @else
                            <div class="bg-gray-100 h-64 flex items-center justify-center text-gray-400">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-gray-600">
                        <p>Used in {{ $wallpaper->songs()->count() }} songs</p>
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('admin.wallpapers.edit', $wallpaper) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Edit Wallpaper
                        </a>
                        <form action="{{ route('admin.wallpapers.destroy', $wallpaper) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this wallpaper?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                Delete Wallpaper
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>