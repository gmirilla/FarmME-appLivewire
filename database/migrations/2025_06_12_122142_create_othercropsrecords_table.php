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
        Schema::create('othercropsrecords', function (Blueprint $table) {
            $table->id();
            $table->integer('farmid');
            $table->string('season');
            $table->string('plotname');
            $table->string('crop');
            $table->string('location');
            $table->float('area',6);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('othercropsrecords');
    }
};
