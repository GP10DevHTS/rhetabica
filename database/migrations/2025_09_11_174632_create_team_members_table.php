<?php

use App\Models\TournamentTeam;
use App\Models\TournamentDebater;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TournamentTeam::class)->cascadeOnDelete();
            $table->foreignIdFor(TournamentDebater::class)->cascadeOnDelete();
            $table->string('uuid')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->string('role')->nullable(); // e.g., 'First Speaker', 'Second Speaker', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
