<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('genre_id')->nullable();
            $table->unsignedBigInteger('wallpaper_id')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });
        
        // Add the foreign keys after all tables are created
        Schema::table('songs', function (Blueprint $table) {
            if (Schema::hasTable('genres')) {
                $table->foreign('genre_id')->references('id')->on('genres')->nullOnDelete();
            }
            if (Schema::hasTable('wallpapers')) {
                $table->foreign('wallpaper_id')->references('id')->on('wallpapers')->nullOnDelete();
            }
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};