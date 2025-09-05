<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tabspaces', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('slug');
        });
    }

    public function down()
    {
        Schema::table('tabspaces', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
