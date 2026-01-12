<?php

namespace App\Filament\Widgets;

use App\Models\ElectionSetting;
use App\Models\User;
use App\Models\Vote;
use Filament\Widgets\Widget;

class ElectionStatusWidget extends Widget
{
    protected string $view = 'filament.widgets.election-status-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public ?string $pollingInterval = '5s';

    public function getViewData(): array
    {
        $settings = ElectionSetting::current();
        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $participationRate = $totalUsers > 0 ? round(($totalVotes / $totalUsers) * 100, 2) : 0;

        $isVotingOpen = $settings?->isVotingOpen() ?? false;
        $hasVotingEnded = false;

        if ($settings && $settings->voting_end) {
            $now = now();
            $hasVotingEnded = $now->greaterThan($settings->voting_end);
        }

        $timeRemaining = null;
        if ($settings && $isVotingOpen && $settings->voting_end) {
            $timeRemaining = now()->diffInSeconds($settings->voting_end, false);
        }

        return [
            'settings' => $settings,
            'totalUsers' => $totalUsers,
            'totalVotes' => $totalVotes,
            'participationRate' => $participationRate,
            'isVotingOpen' => $isVotingOpen,
            'hasVotingEnded' => $hasVotingEnded,
            'timeRemaining' => $timeRemaining,
        ];
    }
}
