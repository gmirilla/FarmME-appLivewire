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
            $table->integer('nooffarmunits')->nullable();
            $table->integer('yearof certification')->nullable();
            $table->string('fname')->nullable();
            $table->string('surname')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('nationalidnumber')->nullable();
            $table->string('gender')->nullable();
            $table->string('noofpermworkers')->nullable();
            $table->string('nooftempworkers')->nullable();
            $table->string('village')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('region')->nullable(); 
            $table->string('crop')->nullable();
            $table->string('cropvariety')->nullable();
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
