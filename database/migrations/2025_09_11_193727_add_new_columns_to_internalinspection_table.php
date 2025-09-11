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
        Schema::table('internalinspections', function (Blueprint $table) {
            //
            $table->integer('approvedby')->nullable();
            $table->date('approveddate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internalinspections', function (Blueprint $table) {
            //
            $table->dropColumn('approvedby');
            $table->dropColumn('approveddate');
        });
    }
};
