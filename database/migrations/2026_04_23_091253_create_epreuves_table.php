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
        Schema::create('epreuves', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->string('enseignant')->nullable();
            $table->string('classe');
            $table->string('serie')->nullable();
            $table->string('matiere');
            $table->year('annee');
            $table->string('semestre');
            $table->string('type');
            $table->string('fichier');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epreuves');
    }
};