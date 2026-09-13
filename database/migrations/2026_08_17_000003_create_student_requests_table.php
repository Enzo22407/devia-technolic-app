<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration des requêtes déposées par les étudiants.
     */
    public function up(): void
    {
        Schema::create('student_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique(); // ex: REQ-2026-0817-001
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // L'étudiant
            $table->foreignId('request_type_id')->constrained('request_types')->onDelete('cascade');
            $table->string('subject');
            $table->text('reason'); // Motif détaillé de la requête
            $table->json('payload')->nullable(); // Données complémentaires selon le type (dates, filière cible, note contestée)
            $table->enum('status', [
                'en_attente', 
                'en_instruction', 
                'avis_pedagogique_requis', 
                'approuvee', 
                'rejetee'
            ])->default('en_attente');
            $table->enum('pedagogical_opinion', ['non_requis', 'en_attente', 'favorable', 'defavorable'])->default('non_requis');
            $table->text('pedagogical_comment')->nullable();
            $table->foreignId('pedagogical_reviewer_id')->nullable()->constrained('users');
            $table->text('decision_comment')->nullable(); // Motif de la décision administrative finale
            $table->foreignId('decided_by')->nullable()->constrained('users');
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_requests');
    }
};
