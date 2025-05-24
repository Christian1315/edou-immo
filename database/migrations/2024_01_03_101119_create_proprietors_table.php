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
        Schema::create('proprietors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("agency")
                ->nullable()
                ->constrained('agencies', 'id')
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("sexe")->nullable();

            $table->string("piece_number")->nullable();
            $table->text("mandate_contrat")->nullable();
            $table->string("comments")->nullable();
            $table->string("adresse")->nullable();

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

            $table->foreignId("card_type")
                ->nullable()
                ->constrained("card_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

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
        Schema::dropIfExists('proprietors');
    }
};
