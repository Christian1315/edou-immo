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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("state")
                ->nullable()
                ->constrained("home_stop_states", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->boolean("state_facture")->default(false);

            $table->foreignId("payement")
                ->nullable()
                ->constrained("payements", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("location")
                ->nullable()
                ->constrained("locationsnew", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("type")
                ->default(1)
                ->constrained("facture_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("status")
                ->default(1)
                ->constrained("facture_statuses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("facture")->nullable();
            $table->text("facture_code")->nullable();
            $table->text("comments")->nullable();
            $table->text("amount");
            $table->string("month")->nullable();
            $table->string("echeance_date")->nullable();

            $table->boolean("is_penality")->default(false);

            $table->text("begin_date")->nullable();
            $table->text("end_date")->nullable();

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
        Schema::dropIfExists('factures');
    }
};
