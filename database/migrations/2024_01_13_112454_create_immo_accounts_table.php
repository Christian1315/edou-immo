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
        Schema::create('immo_accounts', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->text("description");
            $table->string("phone");
            $table->string("email");
            $table->string("balance")->default(0);

            $table->string("status")->nullable();
            $table->foreignId("client")
                ->nullable()
                ->constrained("clients", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->string("type")->nullable();

            $table->string("last_balance")->default(0);
            $table->string("plafond_max")->default(0);
            $table->string("last_operation")->default(0);

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
        Schema::dropIfExists('immo_accounts');
    }
};
