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
        Schema::table('farmunits', function (Blueprint $table) {
            //
            $table->string('crop')->nullable();
            $table->string('system')->nullable();
            $table->float('spacing', 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmunits', function (Blueprint $table) {
            //
            $table->dropColumn('crop','system', 'spacing' );
        });
    }
};
