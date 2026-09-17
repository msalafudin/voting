<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vote;
use App\Models\Candidate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::orderBy('id')->get(['id', 'username', 'full_name', 'name', 'role', 'has_voted', 'voted_at']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|in:VOTER,ADMIN',
        ]);
        $user = User::create([
            'username' => $data['username'], 'full_name' => $data['full_name'],
            'name' => $data['full_name'], 'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'VOTER',
        ]);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'full_name' => 'sometimes|string|max:100',
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:VOTER,ADMIN',
        ]);
        if (isset($data['full_name'])) {
            $data['name'] = $data['full_name'];
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $vote = Vote::where('user_id', $user->id)->first();
            if ($vote) {
                Candidate::whereKey($vote->candidate_id)->decrement('vote_count');
                $vote->delete();
            }
            $user->delete();
        });
        return response()->json(['message' => 'Deleted.']);
    }

    public function resetVote(User $user)
    {
        DB::transaction(function () use ($user) {
            $vote = Vote::where('user_id', $user->id)->first();
            if ($vote) {
                Candidate::whereKey($vote->candidate_id)->decrement('vote_count');
                $vote->delete();
            }
            $user->update(['has_voted' => false, 'voted_at' => null]);
        });
        return response()->json(['message' => 'Vote reset.']);
    }
}
