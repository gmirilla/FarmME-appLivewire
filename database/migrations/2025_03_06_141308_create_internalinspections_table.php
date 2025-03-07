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
        Schema::create('internalinspections', function (Blueprint $table) {
            $table->id();
            $table->foreign('farmid')->references('id')->on('farms');
            $table->foreign('reportid')->references('id')->on('reports');
            $table->decimal('latitude', 11,8)->nullable();
            $table->decimal('longitude', 11,8)->nullable();
            $table->foreign('inspectorid')->references('id')->on('users');
            $table->date('inspectiondate')->nullable();
            $table->string('inspectionstate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internalinspections');
    }
};
