<?php

namespace Database\Seeders;

use App\Models\RequestType;
use App\Models\StudentRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Users
        $student = User::firstOrCreate(
            ['email' => 'etudiant@deviatech.com'],
            [
                'name' => 'Thirdboy Devia',
                'matricule' => 'DEV-2026-001',
                'role' => 'etudiant',
                'filiere' => 'GL2 Prépa (Génie Logiciel)',
                'groupe' => 'Groupe A',
                'phone' => '+237 690 00 11 22',
                'password' => Hash::make('password123'),
            ]
        );

        $manager = User::firstOrCreate(
            ['email' => 'gestionnaire@deviatech.com'],
            [
                'name' => 'Jean-Marc Nguema',
                'matricule' => 'GES-2026-010',
                'role' => 'gestionnaire',
                'filiere' => 'Direction de la Scolarité',
                'phone' => '+237 670 12 34 56',
                'password' => Hash::make('password123'),
            ]
        );

        $pedago = User::firstOrCreate(
            ['email' => 'pedago@deviatech.com'],
            [
                'name' => 'Dr. Alphonse Ekani',
                'matricule' => 'PED-2026-005',
                'role' => 'responsable_pedagogique',
                'filiere' => 'Département Ingénierie & IA',
                'phone' => '+237 655 44 33 22',
                'password' => Hash::make('password123'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@deviatech.com'],
            [
                'name' => 'Administrateur Système',
                'matricule' => 'ADM-2026-000',
                'role' => 'admin_systeme',
                'filiere' => 'Administration Devia',
                'phone' => '+237 699 99 99 99',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Create Request Types
        $types = [
            [
                'title' => 'Correction de Note d\'Examen',
                'code' => 'CORR_NOTE',
                'description' => 'Demande de vérification de report de note ou réévaluation suite à une erreur sur le bulletin.',
                'requires_attachment' => true,
                'requires_pedagogical_review' => true,
            ],
            [
                'title' => 'Attestation de Réussite / Scolarité',
                'code' => 'ATTEST_SCO',
                'description' => 'Demande de délivrance d\'une attestation officielle de scolarité ou de réussite de l\'année académique.',
                'requires_attachment' => false,
                'requires_pedagogical_review' => false,
            ],
            [
                'title' => 'Relevé de Notes Officiel',
                'code' => 'RELEVE_NOTES',
                'description' => 'Demande d\'édition du relevé de notes trimestriel ou annuel sous pli scellé.',
                'requires_attachment' => false,
                'requires_pedagogical_review' => false,
            ],
            [
                'title' => 'Suspension / Annulation de Cours',
                'code' => 'SUSP_COURS',
                'description' => 'Demande de suspension temporaire des cours pour des raisons médicales ou personnelles d\'urgence.',
                'requires_attachment' => true,
                'requires_pedagogical_review' => true,
            ],
            [
                'title' => 'Réclamation Frais de Scolarité',
                'code' => 'RECL_FRAIS',
                'description' => 'Vérification de paiement bancaire ou régularisation de reçu de scolarité.',
                'requires_attachment' => true,
                'requires_pedagogical_review' => false,
            ],
        ];

        foreach ($types as $typeData) {
            RequestType::firstOrCreate(['code' => $typeData['code']], $typeData);
        }

        // 3. Create Sample Requests for demo
        $typeCorr = RequestType::where('code', 'CORR_NOTE')->first();
        $typeAttest = RequestType::where('code', 'ATTEST_SCO')->first();

        if ($typeCorr) {
            StudentRequest::firstOrCreate(
                ['reference_code' => 'REQ-20260913-A101'],
                [
                    'user_id' => $student->id,
                    'request_type_id' => $typeCorr->id,
                    'subject' => 'Erreur de note en Algo & Structure de Données (CC)',
                    'reason' => 'Ma note attribuée sur l\'intranet est 08/20 alors que la copie corrigée physique affiche 16.5/20 avec le sceau de l\'enseignant.',
                    'status' => 'avis_pedagogique_requis',
                    'pedagogical_opinion' => 'en_attente',
                ]
            );
        }

        if ($typeAttest) {
            StudentRequest::firstOrCreate(
                ['reference_code' => 'REQ-20260913-B202'],
                [
                    'user_id' => $student->id,
                    'request_type_id' => $typeAttest->id,
                    'subject' => 'Demande d\'Attestation de Scolarité 2025-2026',
                    'reason' => 'Besoin urgent pour la constitution de mon dossier de stage académique et renouvellement de visa d\'étude.',
                    'status' => 'approuvee',
                    'pedagogical_opinion' => 'non_requis',
                    'decision_comment' => 'Document généré et disponible au guichet de la scolarité.',
                    'decided_by' => $manager->id,
                    'decided_at' => now(),
                ]
            );
        }
    }
}
