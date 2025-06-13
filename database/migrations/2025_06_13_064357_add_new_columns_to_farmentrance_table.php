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
        Schema::table('farmentrances', function (Blueprint $table) {
            //
            $table->integer('internalinspectionid')->nullable();
            $table->integer('inspectorid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmentrances', function (Blueprint $table) {
            //
        });
    }
};
