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
        Schema::create('agency_account_solds', function (Blueprint $table) {
            $table->id();

            $table->foreignId("agency_account")
                ->nullable()
                ->constrained("agency_accounts")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("location")
                ->nullable()
                ->constrained("locationsnew", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("state")
                ->nullable()
                ->constrained("home_stop_states", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("sold")->nullable();
            $table->text("sold_retrieved")->nullable();
            $table->text("sold_added")->nullable();
            $table->text("old_sold")->nullable();
            $table->text("description")->nullable();

            $table->foreignId("water_facture")
                ->nullable()
                ->constrained("location_water_factures", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("electricity_facture")
                ->nullable()
                ->constrained("location_electricty_factures", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            // POUR LA CAISSE CDR
            $table->foreignId("house")
                ->nullable()
                ->constrained("houses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("delete_at")->nullable();
            $table->boolean("visible")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_account_solds');
    }
};
