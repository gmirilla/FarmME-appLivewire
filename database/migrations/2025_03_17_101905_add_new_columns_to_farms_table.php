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
            $table->decimal('latitude', 11,8)->nullable();
            $table->decimal('longitude', 11,8)->nullable();
            $table->decimal('farmarea', 11,8)->nullable();
            $table->string('measurement')->nullable();
            $table->integer('inspectorid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            //
        });
    }
};
