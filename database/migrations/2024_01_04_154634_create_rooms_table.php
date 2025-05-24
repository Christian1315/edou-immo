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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
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

            $table->foreignId("nature")
                ->nullable()
                ->constrained("room_natures", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("type")
                ->nullable()
                ->constrained("room_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("loyer")->nullable();
            $table->text("number");
            $table->text("photo")->nullable();
            $table->text("comments");

            $table->string("gardiennage")->default(0);
            $table->string("rubbish")->default(0);
            $table->string("vidange")->default(0);
            $table->string("cleaning")->default(0);
            $table->string("total_amount")->nullable();

            ##__EAU
            $table->boolean("water")->default(false);
            $table->boolean("water_discounter")->default(false);
            $table->text("unit_price")->nullable();
            $table->boolean("water_card_counter")->default(false);
            $table->boolean("water_conventionnal_counter")->default(false);
            $table->text("water_counter_number")->default(0);
            $table->text("water_counter_start_index")->default(0);
            $table->boolean("forage")->default(false);
            $table->text("forfait_forage")->default(0);

            ###__ELECTRICITY
            $table->boolean("electricity")->default(false);
            $table->text("electricity_unit_price")->nullable();
            $table->boolean("electricity_card_counter")->default(false);
            $table->boolean("electricity_conventionnal_counter")->default(false);
            $table->boolean("electricity_discounter")->default(false);
            $table->text("electricity_counter_start_index")->default(0);
            $table->text("electricity_counter_number")->default(0);

            // $table->boolean("home_banner")->nullable();
            // $table->text("principal_img")->nullable();
            // $table->boolean("publish")->default(true);

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
        Schema::dropIfExists('rooms');
    }
};
