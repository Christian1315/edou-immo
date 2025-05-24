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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_agency")
                ->nullable()
                ->constrained('agencies', 'id')
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->foreignId("agency")
                ->nullable()
                ->constrained('agencies', 'id')
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->string('phone');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_super_admin')->default(false);
            $table->integer('organisation')->nullable();

            $table->string('active_compte_code')->nullable();
            $table->string('compte_actif')->default(false);

            $table->string('pass_code')->nullable();
            $table->string('pass_code_active')->default(true);

            $table->integer("owner")->nullable();
            $table->boolean("is_archive")->default(false);
            $table->boolean("visible")->default(true);

            $table->foreignId("rang_id")
                ->nullable()
                ->constrained('rangs', 'id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreignId("profil_id")
                ->nullable()
                ->constrained('profils', 'id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
