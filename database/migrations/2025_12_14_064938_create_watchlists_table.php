<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // MongoDB ObjectID as string
            $table->string('film_id'); // MongoDB ObjectID as string
            $table->timestamps();
            
            // Ensure unique combination of user and film
            $table->unique(['user_id', 'film_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
