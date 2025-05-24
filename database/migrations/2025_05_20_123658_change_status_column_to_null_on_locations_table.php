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
        Schema::table('locationsnew', function (Blueprint $table) {
            $table->foreignId("status")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /**
         * Remettre l'ancien à sa place
         */
        Schema::table('locationsnew', function (Blueprint $table) {
            $table->foreignId("status")
                ->default(1)
                ->constrained("location_statuses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
        });
    }
};
