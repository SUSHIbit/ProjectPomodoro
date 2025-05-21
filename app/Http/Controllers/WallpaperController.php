<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WallpaperController extends Controller
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
        // Get all wallpapers with song count
        $wallpapers = Wallpaper::withCount('songs')->get();
        
        return view('admin.wallpapers.index', compact('wallpapers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.wallpapers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate wallpaper data
        $request->validate([
            'name' => 'required|string|max:255',
            'image_file' => 'required|image|max:5120', // 5MB max
        ]);
        
        // Store the image file
        $imagePath = $request->file('image_file')->store('images', 'public');
        
        // Create new wallpaper
        Wallpaper::create([
            'name' => $request->name,
            'file_path' => $imagePath,
        ]);
        
        return redirect()->route('admin.wallpapers.index')
            ->with('success', 'Wallpaper created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallpaper $wallpaper)
    {
        return view('admin.wallpapers.edit', compact('wallpaper'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallpaper $wallpaper)
    {
        // Validate wallpaper data
        $request->validate([
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|image|max:5120', // 5MB max
        ]);
        
        // Prepare wallpaper data for update
        $data = [
            'name' => $request->name,
        ];
        
        // Handle image file update if provided
        if ($request->hasFile('image_file')) {
            // Delete the old image file
            if ($wallpaper->file_path) {
                Storage::disk('public')->delete($wallpaper->file_path);
            }
            
            // Store the new image file
            $imagePath = $request->file('image_file')->store('images', 'public');
            $data['file_path'] = $imagePath;
        }
        
        // Update wallpaper
        $wallpaper->update($data);
        
        return redirect()->route('admin.wallpapers.index')
            ->with('success', 'Wallpaper updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallpaper $wallpaper)
    {
        // Check if wallpaper is used by any songs
        if ($wallpaper->songs()->count() > 0) {
            return redirect()->route('admin.wallpapers.index')
                ->with('error', 'Cannot delete wallpaper as it is being used by songs');
        }
        
        // Delete the image file
        if ($wallpaper->file_path) {
            Storage::disk('public')->delete($wallpaper->file_path);
        }
        
        // Delete wallpaper
        $wallpaper->delete();
        
        return redirect()->route('admin.wallpapers.index')
            ->with('success', 'Wallpaper deleted successfully');
    }
    
    /**
     * Preview wallpaper
     */
    public function preview(Wallpaper $wallpaper)
    {
        return view('admin.wallpapers.preview', compact('wallpaper'));
    }
}