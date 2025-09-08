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
        Schema::table('tournament_institutions', function (Blueprint $table) {
            // Invitation details
            $table->timestamp('invited_at')->nullable()->after('name_override');
            $table->foreignIdFor(\App\Models\User::class, 'invited_by')->nullable()->after('invited_at');

            // Confirmation of invitation
            $table->timestamp('confirmed_at')->nullable()->after('invited_by');
            $table->foreignIdFor(\App\Models\User::class, 'confirmed_by')->nullable()->after('confirmed_at');

            // Arrival details
            $table->timestamp('arrived_at')->nullable()->after('confirmed_by');
            $table->foreignIdFor(\App\Models\User::class, 'arrived_recorded_by')->nullable()->after('arrived_at');

            // Internal notes
            $table->text('invitation_notes')->nullable()->after('arrived_recorded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournament_institutions', function (Blueprint $table) {
            $table->dropColumn([
                'invited_at',
                'invited_by',
                'confirmed_at',
                'confirmed_by',
                'arrived_at',
                'arrived_recorded_by',
                'invitation_notes',
            ]);
        });
    }
};
