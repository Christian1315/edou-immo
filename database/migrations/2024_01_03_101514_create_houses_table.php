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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("agency")
                ->nullable()
                ->constrained("agencies", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->string("name");
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->text("comments")->nullable();

            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("proprietor")
                ->nullable()
                ->constrained("proprietors", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("type")
                ->nullable()
                ->constrained("house_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("supervisor")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("city")
                ->nullable()
                ->constrained("cities", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("country")
                ->nullable()
                ->constrained("countries", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("departement")
                ->nullable()
                ->constrained("departements", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("quartier")
                ->nullable()
                ->constrained("quarters", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("zone")
                ->nullable()
                ->constrained("zones", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("commission_percent")->default(10);
            $table->text("proprio_payement_echeance_date")->nullable();
            $table->longText("geolocalisation")->nullable();
            $table->boolean("visible")->default(true);
            $table->text("delete_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
