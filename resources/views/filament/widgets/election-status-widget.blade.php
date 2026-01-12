<x-filament-widgets::widget>
    @php
        $data = $this->getViewData();
        $settings = $data['settings'];
        $totalUsers = $data['totalUsers'];
        $totalVotes = $data['totalVotes'];
        $participationRate = $data['participationRate'];
        $isVotingOpen = $data['isVotingOpen'];
        $hasVotingEnded = $data['hasVotingEnded'];
        $timeRemaining = $data['timeRemaining'];
    @endphp

    @if ($isVotingOpen)
        {{-- VOTING IS LIVE --}}
        <div
            class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 p-8 shadow-2xl">
            {{-- Animated background pattern --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>

            <div class="relative z-10">
                {{-- Header with Live Badge --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="relative flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-white"></span>
                            </span>
                            <span class="text-white font-bold text-2xl uppercase tracking-wider">PEMUNGUTAN SUARA SEDANG
                                BERLANGSUNG</span>
                        </div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <span class="text-white text-sm font-semibold">🔴 LIVE</span>
                    </div>
                </div>

                {{-- Countdown Timer --}}
                @if ($timeRemaining && $timeRemaining > 0)
                    <div class="mb-6" x-data="{
                        timeLeft: {{ $timeRemaining }},
                        days: 0,
                        hours: 0,
                        minutes: 0,
                        seconds: 0,
                        updateCountdown() {
                            if (this.timeLeft <= 0) {
                                window.location.reload();
                                return;
                            }
                            this.days = Math.floor(this.timeLeft / 86400);
                            this.hours = Math.floor((this.timeLeft % 86400) / 3600);
                            this.minutes = Math.floor((this.timeLeft % 3600) / 60);
                            this.seconds = this.timeLeft % 60;
                            this.timeLeft--;
                        }
                    }" x-init="updateCountdown();
                    setInterval(() => updateCountdown(), 1000)">
                        <div class="text-white/90 text-sm font-medium mb-2 uppercase tracking-wide">Waktu Tersisa</div>
                        <div class="grid grid-cols-4 gap-4">
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <div class="text-4xl font-bold text-white" x-text="days"></div>
                                <div class="text-white/80 text-xs uppercase mt-1">Hari</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <div class="text-4xl font-bold text-white" x-text="hours"></div>
                                <div class="text-white/80 text-xs uppercase mt-1">Jam</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <div class="text-4xl font-bold text-white" x-text="minutes"></div>
                                <div class="text-white/80 text-xs uppercase mt-1">Menit</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <div class="text-4xl font-bold text-white" x-text="seconds"></div>
                                <div class="text-white/80 text-xs uppercase mt-1">Detik</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Participation Rate --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white/90 text-sm font-medium uppercase tracking-wide">Tingkat Partisipasi
                            Global</span>
                        <span class="text-white text-2xl font-bold">{{ $participationRate }}%</span>
                    </div>
                    <div class="w-full bg-white/20 backdrop-blur-sm rounded-full h-8 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-white to-yellow-200 h-full rounded-full flex items-center justify-end pr-4 transition-all duration-500 shadow-lg"
                            style="width: {{ $participationRate }}%">
                            <span class="text-green-800 font-bold text-sm">{{ $totalVotes }} /
                                {{ $totalUsers }}</span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-white/80 text-xs">
                        <span>👥 {{ $totalUsers }} Pemilih Terdaftar</span>
                        <span>✅ {{ $totalVotes }} Suara Masuk</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- VOTING HAS ENDED --}}
        <div
            class="relative overflow-hidden rounded-xl bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 p-8 shadow-2xl">
            {{-- Subtle pattern --}}
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>

            <div class="relative z-10">
                {{-- Header with Ended Badge --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h2 class="text-white font-bold text-2xl uppercase tracking-wider">Pemungutan Suara Telah
                                Ditutup</h2>
                            <p class="text-slate-400 text-sm mt-1">Periode pemilihan telah berakhir</p>
                        </div>
                    </div>
                    <div class="bg-slate-600/50 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <span class="text-slate-300 text-sm font-semibold">⏸️ SELESAI</span>
                    </div>
                </div>

                {{-- Final Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Total Voters --}}
                    <div class="bg-slate-600/30 backdrop-blur-sm rounded-lg p-6 border border-slate-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-slate-300 text-sm font-medium uppercase">Total Pemilih</span>
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white">{{ number_format($totalUsers) }}</div>
                    </div>

                    {{-- Votes Cast --}}
                    <div class="bg-slate-600/30 backdrop-blur-sm rounded-lg p-6 border border-slate-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-slate-300 text-sm font-medium uppercase">Suara Masuk</span>
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white">{{ number_format($totalVotes) }}</div>
                    </div>

                    {{-- Participation Rate --}}
                    <div class="bg-slate-600/30 backdrop-blur-sm rounded-lg p-6 border border-slate-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-slate-300 text-sm font-medium uppercase">Partisipasi</span>
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white">{{ $participationRate }}%</div>
                    </div>
                </div>

                {{-- Final Message --}}
                <div class="mt-6 bg-indigo-500/20 backdrop-blur-sm border border-indigo-400/30 rounded-lg p-4">
                    <p class="text-indigo-200 text-center text-sm">
                        🎉 Terima kasih kepada semua pemilih yang telah berpartisipasi dalam proses demokrasi ini
                    </p>
                </div>
            </div>
        </div>
    @endif
</x-filament-widgets::widget>
