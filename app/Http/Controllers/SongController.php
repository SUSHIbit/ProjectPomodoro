<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SongController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all songs with their relationships
        $songs = Song::with(['genre', 'wallpaper'])->get();
        
        return view('admin.songs.index', compact('songs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get genres and wallpapers for dropdown selection
        $genres = Genre::all();
        $wallpapers = Wallpaper::all();
        
        return view('admin.songs.create', compact('genres', 'wallpapers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate song data
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'wallpaper_id' => 'nullable|exists:wallpapers,id',
            'audio_file' => 'required|file|mimes:mp3|max:10240', // 10MB max
        ]);
        
        // Store the audio file
        $audioPath = $request->file('audio_file')->store('audio', 'public');
        
        // Create new song
        Song::create([
            'title' => $request->title,
            'genre_id' => $request->genre_id,
            'wallpaper_id' => $request->wallpaper_id,
            'file_path' => $audioPath,
        ]);
        
        return redirect()->route('admin.songs.index')
            ->with('success', 'Song created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Song $song)
    {
        // Get genres and wallpapers for dropdown selection
        $genres = Genre::all();
        $wallpapers = Wallpaper::all();
        
        return view('admin.songs.edit', compact('song', 'genres', 'wallpapers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Song $song)
    {
        // Validate song data
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'wallpaper_id' => 'nullable|exists:wallpapers,id',
            'audio_file' => 'nullable|file|mimes:mp3|max:10240', // 10MB max
        ]);
        
        // Prepare song data for update
        $data = [
            'title' => $request->title,
            'genre_id' => $request->genre_id,
            'wallpaper_id' => $request->wallpaper_id,
        ];
        
        // Handle audio file update if provided
        if ($request->hasFile('audio_file')) {
            // Delete the old audio file
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            
            // Store the new audio file
            $audioPath = $request->file('audio_file')->store('audio', 'public');
            $data['file_path'] = $audioPath;
        }
        
        // Update song
        $song->update($data);
        
        return redirect()->route('admin.songs.index')
            ->with('success', 'Song updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        // Delete the audio file
        if ($song->file_path) {
            Storage::disk('public')->delete($song->file_path);
        }
        
        // Delete song
        $song->delete();
        
        return redirect()->route('admin.songs.index')
            ->with('success', 'Song deleted successfully');
    }
    
    /**
     * Preview song
     */
    public function preview(Song $song)
    {
        return view('admin.songs.preview', compact('song'));
    }
    
    /**
     * Download song
     */
    public function download(Song $song)
    {
        if ($song->file_path && Storage::disk('public')->exists($song->file_path)) {
            return Storage::disk('public')->download($song->file_path, $song->title . '.mp3');
        }
        
        return back()->with('error', 'File not found');
    }
}