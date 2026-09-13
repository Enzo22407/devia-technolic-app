<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RequestAttachment;
use App\Models\RequestHistory;
use App\Models\RequestType;
use App\Models\StudentRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    public function create()
    {
        $types = RequestType::where('is_active', true)->get();
        return view('requests.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'subject' => 'required|string|max:255',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'payload' => 'nullable|array',
        ]);

        $type = RequestType::findOrFail($validated['request_type_id']);

        if ($type->requires_attachment && !$request->hasFile('attachment')) {
            return back()->withErrors(['attachment' => 'Une pièce justificative est obligatoire pour ce type de requête.'])->withInput();
        }

        $refCode = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $studentRequest = StudentRequest::create([
            'reference_code' => $refCode,
            'user_id' => auth()->id(),
            'request_type_id' => $type->id,
            'subject' => $validated['subject'],
            'reason' => $validated['reason'],
            'payload' => $validated['payload'] ?? [],
            'status' => 'en_attente',
            'pedagogical_opinion' => $type->requires_pedagogical_review ? 'en_attente' : 'non_requis',
        ]);

        // Upload attachment
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            RequestAttachment::create([
                'student_request_id' => $studentRequest->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        // Action Log
        RequestHistory::create([
            'student_request_id' => $studentRequest->id,
            'user_id' => auth()->id(),
            'action' => 'depot_initial',
            'old_status' => null,
            'new_status' => 'en_attente',
            'comment' => 'Dépôt initial de la requête par l\'étudiant.',
        ]);

        // Notify Admins/Managers
        $managers = User::whereIn('role', ['gestionnaire', 'admin_systeme'])->get();
        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'student_request_id' => $studentRequest->id,
                'title' => 'Nouvelle requête déposée',
                'message' => "L'étudiant " . auth()->user()->name . " a déposé la requête #" . $refCode,
                'link' => route('requests.show', $studentRequest->id),
            ]);
        }

        return redirect()->route('dashboard')->with('success', "Votre requête {$refCode} a été soumise avec succès.");
    }

    public function show(StudentRequest $studentRequest)
    {
        $user = auth()->user();

        // Security check
        if ($user->isEtudiant() && $studentRequest->user_id !== $user->id) {
            abort(403, 'Accès interdit à cette requête.');
        }

        $studentRequest->load(['student', 'requestType', 'attachments', 'history.user', 'pedagogicalReviewer', 'decider']);

        return view('requests.show', compact('studentRequest'));
    }
}
