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
        Schema::table('misccodes', function (Blueprint $table) {
            //
            $table->string('crop')->nullable();
            $table->string('system')->nullable();
            $table->float('spacing', 4)->nullable();
            $table->float('farmsize', 4)->nullable();
            $table->integer('farmentranceid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('misccodes', function (Blueprint $table) {
            //
            $table->dropColumn('crop','system', 'spacing','farmsize','farmentranceid' );
        });
    }
};
