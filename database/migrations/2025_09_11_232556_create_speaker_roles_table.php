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
        Schema::create('speaker_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "First Speaker", "Second Speaker", "Adjudicator"
            $table->text('description')->nullable();
            $table->string('abbreviation')->nullable(); // e.g., "1st", "2nd", "Adj"
            $table->integer('order')->default(0); //
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaker_roles');
    }
};
