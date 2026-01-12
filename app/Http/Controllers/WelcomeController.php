<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WelcomeController extends Controller
{
    /**
     * Display the landing page with election stats and status.
     */
    public function index()
    {
        // Singleton Pattern check
        $settings = ElectionSetting::first();

        // Stats
        $totalVotes = Vote::count();

        // Check if voting has ended to determine result display
        $isVotingOpen = $settings ? ElectionSetting::isVotingOpen() : false;
        $hasVotingEnded = $settings && $settings->end_at && now()->greaterThan($settings->end_at);

        // Fetch candidates with different ordering based on voting status
        if ($hasVotingEnded && $totalVotes > 0) {
            // Voting ended: Show LEADERBOARD (ordered by votes DESC)
            $candidates = Candidate::withCount('votes')
                ->orderBy('votes_count', 'desc')
                ->orderBy('order_number', 'asc') // Tie-breaker
                ->get()
                ->map(function ($candidate, $index) use ($totalVotes) {
                    $candidate->rank = $index + 1;
                    $candidate->vote_percentage = $totalVotes > 0
                        ? round(($candidate->votes_count / $totalVotes) * 100, 1)
                        : 0;
                    return $candidate;
                });
        } else {
            // Voting active or upcoming: Show candidates by order_number
            $candidates = Candidate::withCount('votes')
                ->orderBy('order_number')
                ->get();
        }

        return view('welcome', compact('settings', 'totalVotes', 'candidates', 'isVotingOpen', 'hasVotingEnded'));
    }
}
