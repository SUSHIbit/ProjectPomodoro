<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with songs and genres.
     */
    public function index()
    {
        // Get all songs with their relationships
        $songs = Song::with(['genre', 'wallpaper'])->get();
        
        // Get all genres for filtering options
        $genres = Genre::all();
        
        return view('dashboard', compact('songs', 'genres'));
    }
    
    /**
     * Filter songs by genre
     */
    public function filterByGenre(Request $request)
    {
        $genreId = $request->genre_id;
        
        // If genre_id is provided, filter songs by that genre
        if ($genreId) {
            $songs = Song::with(['genre', 'wallpaper'])
                ->where('genre_id', $genreId)
                ->get();
        } else {
            // Otherwise get all songs
            $songs = Song::with(['genre', 'wallpaper'])->get();
        }
        
        $genres = Genre::all();
        
        return view('dashboard', compact('songs', 'genres'));
    }
    
    /**
     * Get song details for AJAX requests
     */
    public function getSongDetails(Song $song)
    {
        return response()->json([
            'id' => $song->id,
            'title' => $song->title,
            'genre' => $song->genre ? $song->genre->name : null,
            'file_path' => Storage::url($song->file_path),
            'wallpaper' => $song->wallpaper ? Storage::url($song->wallpaper->file_path) : null,
        ]);
    }
}