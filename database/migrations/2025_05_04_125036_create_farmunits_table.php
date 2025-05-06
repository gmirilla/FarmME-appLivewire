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
        Schema::create('farmunits', function (Blueprint $table) {
            $table->id();
            $table->integer('farmid');
            $table->float('fuarea', 10)->nullable();
            $table->decimal('fulatitude',11,8 )->nullable();
            $table->decimal('fulongitude',11,8 )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmunits');
    }
};
