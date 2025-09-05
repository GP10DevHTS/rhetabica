<?php

use App\Models\ParticipantCategory;
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
        Schema::table('tournament_debaters', function (Blueprint $table) {
            $table->foreignIdFor(ParticipantCategory::class)->nullable()->after('nickname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_debaters', function (Blueprint $table) {
            //
        });
    }
};
