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
        Schema::table('paiement_initiations', function (Blueprint $table) {
            $table->string("stats_stoped_day")->nullable();
            $table->text("recovery_rapport")->nullable();
            $table->boolean("proprietor_paid")->default(false);
            $table->integer("old_state")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiement_initiations', function (Blueprint $table) {
            //
        });
    }
};
