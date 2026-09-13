<?php

namespace App\Http\Controllers;

use App\Models\StudentRequest;
use App\Models\User;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isEtudiant()) {
            $requests = StudentRequest::with(['requestType', 'attachments'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = [
                'total' => $requests->count(),
                'en_attente' => $requests->where('status', 'en_attente')->count(),
                'en_instruction' => $requests->whereIn('status', ['en_instruction', 'avis_pedagogique_requis'])->count(),
                'approuvees' => $requests->where('status', 'approuvee')->count(),
                'rejetees' => $requests->where('status', 'rejetee')->count(),
            ];

            return view('dashboard.student', compact('user', 'requests', 'stats'));
        }

        if ($user->isGestionnaire() || $user->isResponsablePedagogique()) {
            $query = StudentRequest::with(['student', 'requestType', 'attachments']);

            if ($user->isResponsablePedagogique()) {
                $query->where('status', 'avis_pedagogique_requis');
            }

            $requests = $query->orderBy('created_at', 'desc')->get();

            $stats = [
                'total' => StudentRequest::count(),
                'en_attente' => StudentRequest::where('status', 'en_attente')->count(),
                'avis_pedagogique' => StudentRequest::where('status', 'avis_pedagogique_requis')->count(),
                'approuvees' => StudentRequest::where('status', 'approuvee')->count(),
                'rejetees' => StudentRequest::where('status', 'rejetee')->count(),
            ];

            return view('dashboard.manager', compact('user', 'requests', 'stats'));
        }

        if ($user->isAdminSysteme()) {
            $stats = [
                'total_users' => User::count(),
                'total_etudiants' => User::where('role', 'etudiant')->count(),
                'total_requests' => StudentRequest::count(),
                'approval_rate' => StudentRequest::count() > 0 
                    ? round((StudentRequest::where('status', 'approuvee')->count() / StudentRequest::count()) * 100, 1) 
                    : 0,
            ];

            $requestTypes = RequestType::withCount('studentRequests')->get();
            $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

            return view('dashboard.admin', compact('user', 'stats', 'requestTypes', 'recentUsers'));
        }

        abort(403);
    }
}
