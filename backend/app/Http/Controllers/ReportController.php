<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidate;

class ReportController extends Controller
{
    public function ranking()
    {
        $total = User::where('role', 'VOTER')->count();
        $voted = User::where('role', 'VOTER')->where('has_voted', true)->count();
        return response()->json([
            'total_voters' => $total,
            'voted' => $voted,
            'not_voted' => $total - $voted,
            'participation_rate' => $total ? round($voted / $total * 100, 1) : 0,
            'ranking' => Candidate::orderByDesc('vote_count')->orderBy('candidate_number')->get(),
        ]);
    }
}
