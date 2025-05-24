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
        Schema::table('locationsnew', function (Blueprint $table) {
            $table->text("echeance_date")->nullable()->change();
            $table->text("latest_loyer_date")->nullable()->change();
            $table->text("integration_date")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locationsnew', function (Blueprint $table) {
            //
        });
    }
};
