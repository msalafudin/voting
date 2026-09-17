<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Candidate::orderBy('candidate_number')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'candidate_number' => 'required|integer|unique:candidates,candidate_number',
            'full_name' => 'required|string|max:100',
            'vision' => 'required|string', 'mission' => 'required|string',
            'photo_url' => 'nullable|string|max:255',
        ]);
        return response()->json(Candidate::create($data), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return response()->json($candidate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $data = $request->validate([
            'candidate_number' => 'sometimes|integer|unique:candidates,candidate_number,' . $candidate->id,
            'full_name' => 'sometimes|string|max:100',
            'vision' => 'sometimes|string', 'mission' => 'sometimes|string',
            'photo_url' => 'nullable|string|max:255',
        ]);
        $candidate->update($data);
        return response()->json($candidate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
