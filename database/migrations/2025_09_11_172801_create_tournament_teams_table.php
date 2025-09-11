<?php

use App\Models\Tournament;
use App\Models\User;
use App\Models\ParticipantCategory;
use App\Models\TournamentInstitution;
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
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tournament::class)->cascadeOnDelete();
            $table->string('uuid')->nullable();
            $table->string('name');
            $table->foreignIdFor(ParticipantCategory::class)->nullable()->nullOnDelete();
            $table->timestamps();
            $table->foreignIdFor(TournamentInstitution::class)->nullable()->nullOnDelete(); // optional institution association
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_teams');
    }
};
