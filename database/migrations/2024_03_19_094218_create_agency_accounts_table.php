<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     **/

    public function up(): void
    {
        Schema::create('agency_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("agency")
                ->nullable()
                ->constrained("agencies")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("account")
                ->nullable()
                ->constrained("immo_accounts")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_accounts');
    }
};
