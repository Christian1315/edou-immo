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
        Schema::create('locator_avalisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("locator")
                ->nullable()
                ->constrained("locataires", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("ava_parent_link")->nullable();
            $table->string("ava_name")->nullable();
            $table->string("ava_prenom")->nullable();
            $table->string("ava_phone")->nullable();

            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
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
        Schema::dropIfExists('locator_avalisors');
    }
};
