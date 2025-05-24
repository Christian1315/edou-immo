DEVO<?php

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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("type")
                ->nullable()
                ->constrained("client_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("city")
                ->nullable()
                ->constrained("cities", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("name")->nullable();
            $table->text("company")->nullable();
            $table->text("rccm")->nullable();
            $table->text("number")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->text("comments")->nullable();
            $table->string("country_prefix")->default(229);

            $table->string("sexe")->nullable();

            $table->boolean("is_proprietor")->default(false);
            $table->boolean("is_locator")->default(false);

            $table->boolean("is_render")->default(false);
            $table->boolean("is_avaliseur")->default(false);

            $table->boolean("visible")->default(true);
            $table->string("delete_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
