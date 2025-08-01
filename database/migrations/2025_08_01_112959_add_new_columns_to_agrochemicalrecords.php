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
        Schema::table('agrochemicalrecords', function (Blueprint $table) {
            //
            $table->float('farmsize',4)->nullable();
            $table->boolean('ppeused')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agrochemicalrecords', function (Blueprint $table) {
            //
            $table->dropColumn('farmsize','ppeused');
        });
    }
};
