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
        Schema::table('farmentrances', function (Blueprint $table) {
            //
            $table->string('surname')->nullable();
            $table->string('fname')->nullable();
            $table->string('farmcode')->nullable();
            $table->string('nationalidno')->nullable();
            $table->string('yob')->nullable();
            $table->string('phoneno')->nullable();
            $table->string('householdsize')->nullable();
            $table->string('address')->nullable();
            $table->date('lastinspection')->nullable();
            $table->string('inpsectionresult')->nullable();
            $table->string('crop')->nullable();
            $table->string('cropvariety')->nullable();
            $table->date('regdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmentrances', function (Blueprint $table) {
            //
            
        });
    }
};
