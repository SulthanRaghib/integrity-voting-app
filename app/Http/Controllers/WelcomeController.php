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
        $candidates = Candidate::orderBy('order_number')->get(['id', 'name', 'photo_path']);

        // Voting Status check helpers for the view
        $isVotingOpen = $settings ? \App\Models\ElectionSetting::isVotingOpen() : false;

        return view('welcome', compact('settings', 'totalVotes', 'candidates', 'isVotingOpen'));
    }
}
