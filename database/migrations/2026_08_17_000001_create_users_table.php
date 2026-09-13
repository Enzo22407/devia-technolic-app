<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration de la table des utilisateurs de l'IME.
     * Rôles : etudiant, gestionnaire, responsable_pedagogique, admin_systeme
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('matricule')->unique()->nullable(); // Numéro matricule étudiant ou ID agent
            $table->enum('role', ['etudiant', 'gestionnaire', 'responsable_pedagogique', 'admin_systeme'])->default('etudiant');
            $table->string('filiere')->nullable(); // Ex: GL1 Prépa, Marketing, Finance
            $table->string('groupe')->nullable(); // Ex: Groupe A, GL1-B
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
