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
        Schema::create('location_water_factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("state")
                ->nullable()
                ->constrained("stop_house_water_states", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->boolean("state_facture")->default(false);

            $table->foreignId("location")
                ->nullable()
                ->constrained("locationsnew", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("start_index")->nullable();
            $table->text("end_index")->nullable();
            $table->text("consomation")->nullable();
            $table->text("comments")->nullable();
            $table->text("amount")->nullable();

            $table->boolean("visible")->default(true);
            $table->boolean("paid")->default(false);
            $table->string("delete_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_water_factures');
    }
};
