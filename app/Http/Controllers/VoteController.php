<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ElectionSetting;
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

        // Optional: Pass 'isVotingOpen' to view if you want to disable UI based on time
        // $isVotingOpen = ElectionSetting::isVotingOpen();

        $candidates = Candidate::orderBy('order_number')->get();

        return view('voting.index', compact('candidates', 'hasVoted'));
    }

    public function store(Request $request)
    {
        // ==========================================
        // RACE CONDITION PREVENTION: Multi-Layer Defense
        // ==========================================

        // Layer 0: Temporal Integrity Check (Fast fail before locking)
        if (!ElectionSetting::isVotingOpen()) {
            return redirect()->route('welcome')->with('error', 'Periode pemilihan telah berakhir. Anda tidak dapat memberikan suara saat ini.');
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'device_hash' => 'required|string',
        ]);

        try {
            // ==========================================
            // Layer 1: PESSIMISTIC LOCKING + TRANSACTION
            // ==========================================
            DB::beginTransaction();

            // Lock the user row to prevent concurrent voting attempts
            // This forces other requests for THIS user to wait in queue
            $user = \App\Models\User::where('id', Auth::id())
                ->lockForUpdate()
                ->first();

            if (!$user) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'User tidak ditemukan.']);
            }

            // Re-check voting period inside transaction (after acquiring lock)
            $settings = ElectionSetting::current();
            if (!$settings || !$settings->isVotingOpen()) {
                DB::rollBack();
                return redirect()->route('welcome')->with('error', 'Periode pemilihan telah berakhir.');
            }

            // ==========================================
            // Layer 2: ATOMIC RE-CHECK (Inside Lock)
            // ==========================================

            // Check if user has already voted (inside lock to prevent race)
            $existingVote = Vote::where('user_id', $user->id)->first();
            if ($existingVote) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Peringatan Keamanan: Anda sudah memberikan suara.']);
            }

            // Check if device has already voted
            $deviceVote = Vote::where('device_hash', $request->device_hash)->first();
            if ($deviceVote) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Peringatan Keamanan: Anda sudah memberikan suara.']);
            }

            // ==========================================
            // Layer 3: DATABASE CONSTRAINT (Last Defense)
            // ==========================================

            // Create Vote (unique constraint on user_id prevents duplicate at DB level)
            Vote::create([
                'user_id' => $user->id,
                'candidate_id' => $request->candidate_id,
                'device_hash' => $request->device_hash,
                'ip_address' => $request->ip(),
                'device_info' => $request->header('User-Agent'),
            ]);

            // Increment Candidate Vote Count
            Candidate::where('id', $request->candidate_id)->increment('votes_count');

            // Commit transaction - all changes are now atomic
            DB::commit();

            return redirect()->route('voting.index')->with('success', 'Suara Anda berhasil tercatat! Terima kasih atas partisipasi Anda.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Catch duplicate entry error (error code 23000 = Integrity constraint violation)
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                // This means the database-level unique constraint caught the race condition
                Log::warning("Race condition detected and prevented by DB constraint for user: " . Auth::id());
                return redirect()->back()->withErrors(['error' => 'Peringatan Keamanan: Anda sudah memberikan suara.']);
            }

            // Other database errors
            Log::error("Database error during voting: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'candidate_id' => $request->candidate_id,
            ]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Unexpected error during voting: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'candidate_id' => $request->candidate_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan yang tidak terduga. Silakan hubungi administrator.']);
        }
    }
}
