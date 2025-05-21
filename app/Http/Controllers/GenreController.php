<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GenreController extends Controller
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
        // Get all genres with song count
        $genres = Genre::withCount('songs')->get();
        
        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate genre data
        $request->validate([
            'name' => 'required|string|max:255|unique:genres',
        ]);
        
        // Create new genre
        Genre::create([
            'name' => $request->name,
        ]);
        
        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        // Validate genre data
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('genres')->ignore($genre->id),
            ],
        ]);
        
        // Update genre
        $genre->update([
            'name' => $request->name,
        ]);
        
        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        // Check if genre is used by any songs
        if ($genre->songs()->count() > 0) {
            return redirect()->route('admin.genres.index')
                ->with('error', 'Cannot delete genre as it is being used by songs');
        }
        
        // Delete genre
        $genre->delete();
        
        return redirect()->route('admin.genres.index')
            ->with('success', 'Genre deleted successfully');
    }
}