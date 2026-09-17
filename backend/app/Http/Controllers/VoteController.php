<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\Vote;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['candidate_id' => 'required|exists:candidates,id']);
        $user = $request->user();
        if ($user->has_voted) {
            return response()->json(['message' => 'Anda sudah menggunakan hak pilih.'], 422);
        }
        try {
            DB::transaction(function () use ($user, $data) {
                Vote::create(['user_id' => $user->id, 'candidate_id' => $data['candidate_id']]);
                Candidate::whereKey($data['candidate_id'])->increment('vote_count');
                $user->update(['has_voted' => true, 'voted_at' => now()]);
            });
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal menyimpan suara.'], 422);
        }
        return response()->json(['message' => 'Suara berhasil dicatat.'], 201);
    }
}
