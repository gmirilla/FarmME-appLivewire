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
        Schema::create('farmunityields', function (Blueprint $table) {
            $table->id();
            $table->integer('farmid');
            $table->integer('farmunitid');
            $table->integer('year');
            $table->float('estyield',10)->default(0.0);
            $table->float('actualyield',10)->nullable();
            $table->string('comments')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmunityields');
    }
};
