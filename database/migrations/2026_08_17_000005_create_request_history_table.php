<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Traçabilité / Journal d'actions centralisé pour chaque requête.
     */
    public function up(): void
    {
        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_request_id')->constrained('student_requests')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users'); // Auteur de l'action
            $table->string('action'); // ex: depot_initial, passage_en_instruction, avis_pedagogique_emis, approbation, rejet
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_history');
    }
};
