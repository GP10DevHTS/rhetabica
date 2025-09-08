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
        Schema::create('tournament_institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Tournament::class);
            $table->foreignIdFor(\App\Models\Institution::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->uuid('uuid')->nullable();
            $table->string('name_override')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_institutions');
    }
};
