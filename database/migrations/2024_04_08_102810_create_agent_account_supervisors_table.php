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
        Schema::create('agent_account_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("agent_account")
                ->nullable()
                ->constrained("users")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("supervisor")
                ->nullable()
                ->constrained("users")
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
        Schema::dropIfExists('agent_account_supervisors');
    }
};
