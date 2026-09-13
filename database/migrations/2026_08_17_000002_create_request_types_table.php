<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration des types de requêtes configurables par l'administrateur.
     */
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ex: autorisation_absence, changement_filiere, reclamation_note
            $table->string('title'); // Intitulé lisible
            $table->text('description')->nullable();
            $table->boolean('requires_attachment')->default(false);
            $table->boolean('requires_pedagogical_review')->default(false); // Si validation du resp. pédagogique requise
            $table->json('form_fields')->nullable(); // Définition dynamique des champs spécifiques (JSON)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_types');
    }
};
