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
        Schema::create('agrochemicalrecords', function (Blueprint $table) {
            $table->id();
            $table->integer('farmid');
            $table->integer('entranceid');
            $table->string('herbicidename');
            $table->string('quantity');
            $table->string('nameofperson');
            $table->integer('hectaresapplied');
            $table->integer('season');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrochemicalrecords');
    }
};
