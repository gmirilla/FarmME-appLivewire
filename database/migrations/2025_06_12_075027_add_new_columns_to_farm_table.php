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
        Schema::table('farms', function (Blueprint $table) {
            //
            $table->string('farmentrance')->nullable();
            $table->string('yob')->nullable();
            $table->string('householdsize')->nullable();
            $table->string('address')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            //
            $table->dropColumn('farmentrance');
        });
    }
};
