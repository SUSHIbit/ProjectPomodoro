<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'file_path'];
    
    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}