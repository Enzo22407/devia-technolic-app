<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\RequestHistory;
use App\Models\StudentRequest;
use Illuminate\Http\Request;

class RequestManagerController extends Controller
{
    public function updateStatus(Request $request, StudentRequest $studentRequest)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'status' => 'required|in:en_instruction,avis_pedagogique_requis,approuvee,rejetee',
            'comment' => 'required|string|min:5',
        ]);

        $oldStatus = $studentRequest->status;
        $newStatus = $validated['status'];

        $studentRequest->status = $newStatus;

        if (in_array($newStatus, ['approuvee', 'rejetee'])) {
            $studentRequest->decision_comment = $validated['comment'];
            $studentRequest->decided_by = $user->id;
            $studentRequest->decided_at = now();
        }

        $studentRequest->save();

        // Audit Trail
        RequestHistory::create([
            'student_request_id' => $studentRequest->id,
            'user_id' => $user->id,
            'action' => 'changement_statut_' . $newStatus,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'comment' => $validated['comment'],
        ]);

        // Notify Student
        Notification::create([
            'user_id' => $studentRequest->user_id,
            'student_request_id' => $studentRequest->id,
            'title' => 'Mise à jour de votre requête #' . $studentRequest->reference_code,
            'message' => "Le statut de votre requête est passé à : " . $studentRequest->status_label . ". Motifs : " . $validated['comment'],
            'link' => route('requests.show', $studentRequest->id),
        ]);

        return back()->with('success', 'Statut de la requête mis à jour avec succès.');
    }

    public function submitPedagogicalOpinion(Request $request, StudentRequest $studentRequest)
    {
        $user = auth()->user();

        if (!$user->isResponsablePedagogique() && !$user->isAdminSysteme()) {
            abort(403);
        }

        $validated = $request->validate([
            'opinion' => 'required|in:favorable,defavorable',
            'comment' => 'required|string|min:5',
        ]);

        $studentRequest->pedagogical_opinion = $validated['opinion'];
        $studentRequest->pedagogical_comment = $validated['comment'];
        $studentRequest->pedagogical_reviewer_id = $user->id;
        $studentRequest->status = 'en_instruction'; // Transmis au gestionnaire pour décision finale
        $studentRequest->save();

        // Audit Trail
        RequestHistory::create([
            'student_request_id' => $studentRequest->id,
            'user_id' => $user->id,
            'action' => 'avis_pedagogique_emis',
            'old_status' => 'avis_pedagogique_requis',
            'new_status' => 'en_instruction',
            'comment' => "Avis pédagogique (" . ucfirst($validated['opinion']) . ") : " . $validated['comment'],
        ]);

        return back()->with('success', 'Avis pédagogique enregistré et dossier transmis au gestionnaire.');
    }
}
