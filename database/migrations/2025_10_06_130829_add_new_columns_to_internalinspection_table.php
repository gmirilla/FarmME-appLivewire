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
        Schema::table('internalinspections', function (Blueprint $table) {
            //



            $table->integer('verifiedby')->nullable();
            $table->date('verifieddate')->nullable();
            $table->string('verificationcomments')->nullable();
            $table->string('IMSmanager_approval')->nullable();
            $table->integer('ecomm_checked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internalinspections', function (Blueprint $table) {
            //


            $table->dropColumn('verifiedby');
            $table->dropColumn('verifieddate');
            $table->dropColumn('verificationcomments');
            $table->dropColumn('IMSmanager_approval');
            $table->dropColumn('ecomm_checked');
        });
    }
};
