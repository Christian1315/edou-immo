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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->string("ifu");
            $table->longText("denomination");
            $table->string("form_juridique");
            $table->text("principal_activity");
            $table->string("activity_area");
            $table->string("creation_date");
            $table->string("phone");
            $table->string("email");
            $table->string("departement");
            $table->string("adresse");
            $table->string("rccm");
            $table->boolean('visible')->default(true);
            $table->text('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
