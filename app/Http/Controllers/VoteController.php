<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    public function index()
    {
        $hasVoted = false;
        if (Auth::check()) {
            $hasVoted = Vote::where('user_id', Auth::id())->exists();
        }

        $candidates = Candidate::orderBy('order_number')->get();

        return view('voting.index', compact('candidates', 'hasVoted'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'device_hash' => 'required|string',
        ]);

        $user = Auth::user();

        // Integrity Check 1: User has voted check
        if (Vote::where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Security Alert: You have already cast your vote.']);
        }

        // Integrity Check 2: Device has voted check
        if (Vote::where('device_hash', $request->device_hash)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Security Alert: This device has already been used to vote.']);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Create Vote
                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $request->candidate_id,
                    'device_hash' => $request->device_hash,
                    'ip_address' => $request->ip(),
                    'device_info' => $request->header('User-Agent'),
                ]);

                // Increment Candidate Count
                Candidate::where('id', $request->candidate_id)->increment('votes_count');
            });

            return redirect()->route('voting.index')->with('success', 'Vote successfully cast! Integrity verified.');
        } catch (\Exception $e) {
            Log::error("Voting Error: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'System Error: Vote could not be processed.']);
        }
    }
}
