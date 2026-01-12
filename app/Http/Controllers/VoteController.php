<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    public function index()
    {
        $hasVoted = false;
        if (auth()->check()) {
            $hasVoted = Vote::where('user_id', auth()->id())->exists();
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

        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // 1. Check if User already voted
        if (Vote::where('user_id', $user->id)->exists()) {
            return back()->withErrors(['error' => 'You have already voted.']);
        }

        // 2. Integrity Check: Device Hash (Strict: 1 Vote per Device)
        if (Vote::where('device_hash', $request->device_hash)->exists()) {
            return back()->withErrors(['error' => 'This device has already been used to vote. Integrity check failed.']);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Lock candidate for update to safely increment?
                // increment() is atomic enough for counter usually, but transaction ensures sync with vote record.

                Vote::create([
                    'user_id' => $user->id,
                    'candidate_id' => $request->candidate_id,
                    'device_hash' => $request->device_hash,
                    'device_info' => $request->header('User-Agent'),
                    'ip_address' => $request->ip(),
                ]);

                Candidate::where('id', $request->candidate_id)->increment('votes_count');
            });

            return redirect()->route('voting.index')->with('success', 'Vote cast successfully!');
        } catch (\Exception $e) {
            Log::error('Vote failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'System error casting vote. Please try again.']);
        }
    }
}
