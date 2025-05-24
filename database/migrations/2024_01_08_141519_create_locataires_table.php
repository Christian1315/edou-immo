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
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->foreignId("agency")
                ->nullable()
                ->constrained("agencies", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("email")->nullable();
            $table->string("sexe");
            $table->string("prenom");
            $table->string("phone");
            // $table->text("piece_number");
            $table->text("mandate_contrat")->nullable();
            $table->text("comments")->nullable();
            $table->string("name");
            $table->string("card_id");
            $table->string("adresse");

            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("card_type")
                ->nullable()
                ->constrained("card_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("departement")
                ->nullable()
                ->constrained("departements", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("country")
                ->nullable()
                ->constrained("countries", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->boolean("prorata")->default(false);
            $table->text("prorata_date")->nullable();

            $table->boolean("discounter")->default(false);
            $table->text("kilowater_price")->default(0);

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
        Schema::dropIfExists('locataires');
    }
};
