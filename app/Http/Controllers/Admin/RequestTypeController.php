<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use Illuminate\Http\Request;

class RequestTypeController extends Controller
{
    public function index()
    {
        $types = RequestType::withCount('studentRequests')->orderBy('created_at', 'desc')->get();
        return view('admin.request_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:request_types',
            'description' => 'nullable|string',
            'requires_attachment' => 'boolean',
            'requires_pedagogical_review' => 'boolean',
        ]);

        RequestType::create([
            'title' => $validated['title'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'] ?? '',
            'requires_attachment' => $request->has('requires_attachment'),
            'requires_pedagogical_review' => $request->has('requires_pedagogical_review'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Nouveau type de requête configuré avec succès.');
    }

    public function toggle(RequestType $requestType)
    {
        $requestType->is_active = !$requestType->is_active;
        $requestType->save();

        return back()->with('success', 'Statut du type de requête mis à jour.');
    }
}
