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
        Schema::create('locationsnew', function (Blueprint $table) {
            $table->id();
            $table->foreignId("agency")
                ->nullable()
                ->constrained("agencies", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->foreignId("house")
                ->nullable()
                ->constrained("houses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("room")
                ->nullable()
                ->constrained("rooms", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("locataire")
                ->nullable()
                ->constrained("locataires", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("type")
                ->nullable()
                ->constrained("location_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("status")
                ->default(1)
                ->constrained("location_statuses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("payment_mode")
                ->nullable()
                ->constrained("paiement_modules", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("moved_by")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("suspend_by")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("move_date")->nullable();
            $table->text("move_comments")->nullable();

            $table->text("suspend_date")->nullable();
            $table->text("suspend_comments")->nullable();

            $table->text("caution_bordereau")->nullable();
            $table->string("loyer");
            $table->string("prestation");
            $table->string("numero_contrat");

            $table->text("caution_water");
            $table->string("total_amount")->nullable();

            $table->text("comments");
            $table->text("img_contrat")->nullable();

            $table->text("caution_electric")->nullable();

            $table->text("electric_counter")->nullable();
            $table->text("water_counter")->nullable();

            ###___les arrierrées en electricité et eau
            $table->text("electric_unpaid")->nullable();
            $table->text("water_unpaid")->nullable();

            $table->string("caution_number");
            $table->string("frais_peiture")->default(0);

            $table->text("previous_echeance_date")->nullable();
            $table->text("echeance_date");
            $table->text("latest_loyer_date");
            $table->text("next_loyer_date")->nullable();
            $table->text("img_prestation")->nullable();
            $table->text("effet_date")->nullable();
            $table->text("integration_date");

            $table->boolean("discounter")->default(false);
            $table->text("kilowater_price")->default(0);

            $table->boolean("visible")->default(true);

            $table->boolean("pre_paid")->default(false);
            $table->boolean("post_paid")->default(false);

            $table->text("delete_at")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locationsnew');
    }
};
