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
        Schema::create('reportsections', function (Blueprint $table) {
            $table->id();
            $table->integer('reportid');
            $table->foreign('reportid')->references('id')->on('reports');
            $table->string('sectionname');
            $table->integer('section_seq');
            $table->string('sectionstate');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportsections');
    }
};
