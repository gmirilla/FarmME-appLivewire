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
        Schema::table('inspectionanswers', function (Blueprint $table) {
            //
            $table->integer('internalinspectionid')->nullable();
            $table->integer('questionid');
            $table->integer('sectionid')->nullable();
            $table->integer('reportid')->nullable();
            $table->integer('answer')->nullable();
            $table->string('sectionidcomments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspectionanswers', function (Blueprint $table) {
            //
        });
    }
};
