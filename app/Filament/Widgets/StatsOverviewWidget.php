<?php

namespace App\Filament\Widgets;

use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalVoters = User::count();
        $votesCast = Vote::count();

        // Get leading candidate
        $leadingCandidate = Candidate::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->first();

        $leadingCandidateName = $leadingCandidate
            ? $leadingCandidate->name
            : 'Belum Ada Data';

        $leadingCandidateVotes = $leadingCandidate
            ? $leadingCandidate->votes_count
            : 0;

        return [
            Stat::make('Total Pemilih', $totalVoters)
                ->description('Pengguna terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Suara Masuk', $votesCast)
                ->description('Total suara yang telah diberikan')
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success')
                ->chart([3, 5, 6, 7, 8, 9, 10, 12]),

            Stat::make('Kandidat Teratas', $leadingCandidateName)
                ->description($leadingCandidateVotes . ' suara')
                ->descriptionIcon('heroicon-o-trophy')
                ->color('warning')
                ->chart(array_fill(0, 8, $leadingCandidateVotes > 0 ? 5 : 0)),
        ];
    }
}
