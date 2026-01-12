@extends('layouts.app')

@section('navbar-actions')
    @auth
        <a href="{{ route('voting.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
            Dasboard Voting
        </a>

        <div class="h-5 w-px bg-slate-200 mx-1 hidden sm:block"></div>

        <form action="{{ route('logout') }}" method="POST" class="flex items-center">
            @csrf
            <button type="submit"
                class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors flex items-center gap-1.5 px-2 py-1 hover:bg-red-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
                <span class="hidden sm:inline">Keluar</span>
            </button>
        </form>
    @else
        <a id="login-button" href="{{ route('login') }}"
            class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
            Login
        </a>
    @endauth
@endsection

@section('content')
    <div class="relative overflow-hidden bg-white">
        <div class="absolute inset-0">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1540910419868-474947cebacb?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center opacity-5">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/50 via-white to-white"></div>
        </div>

        <div class="relative container mx-auto px-4 py-16 sm:py-24 lg:py-32 flex flex-col items-center text-center">
            <!-- Flash Messages -->
            @if (session('error'))
                <div class="mb-6 max-w-2xl w-full bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            <span
                class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-600 ring-1 ring-inset ring-indigo-600/10 mb-6">
                Portal Pemilihan Resmi {{ date('Y') }}
            </span>

            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl mb-6 max-w-4xl">
                Suara Anda, Kekuatan Anda. <br>
                <span class="text-indigo-600">Pemilihan yang Aman & Transparan.</span>
            </h1>

            <p class="mx-auto max-w-xl text-lg text-slate-600 mb-10">
                Berpartisipasi dalam proses demokrasi dengan sistem pemilihan berintegritas tinggi kami.
                Cepat, aman, dan mudah diakses.
            </p>

            <!-- Alpine.js Countdown & Status -->

            <!-- Floating Help Button -->
            <button id="start-welcome-tour" onclick="startWelcomeTour()" aria-label="Bantuan Cara Menggunakan"
                class="fixed z-50 right-5 bottom-6 md:bottom-8 bg-indigo-600 text-white px-4 py-3 rounded-full shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm font-semibold transition-transform hover:scale-105 active:scale-95">
                Bantuan/Cara Pakai
            </button>
            <div id="voting-timer" x-data="timer('{{ $settings?->end_at?->toIso8601String() }}', '{{ $settings?->start_at?->toIso8601String() }}', {{ $hasVotingEnded ? 'true' : 'false' }})" x-init="init()"
                class="w-full max-w-3xl mx-auto mb-12 min-h-[160px]">

                <!-- Status Badge -->
                <div class="mb-8" x-cloak>
                    <template x-if="status === 'upcoming'">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-semibold border border-yellow-200">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                            </span>
                            Pemilihan Segera Dimulai
                        </span>
                    </template>
                    <template x-if="status === 'active'">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold border border-green-200">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            Pemilihan Sedang Berlangsung
                        </span>
                    </template>
                    <template x-if="status === 'closed'">
                        <span
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-800 font-semibold border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Pemilihan Telah Ditutup
                        </span>
                    </template>
                </div>

                <!-- Countdown Cards -->
                <template x-if="status !== 'closed' && status !== 'loading'">
                    <div>
                        <div class="grid grid-cols-4 gap-4 sm:gap-6 max-w-lg mx-auto">
                            <div
                                class="flex flex-col p-4 bg-white rounded-xl shadow-lg border border-slate-100 transform hover:scale-105 transition-transform duration-200">
                                <span class="text-2xl sm:text-4xl font-bold text-indigo-600 tabular-nums"
                                    x-text="days">00</span>
                                <span class="text-xs uppercase font-semibold text-slate-400 mt-1">Hari</span>
                            </div>
                            <div
                                class="flex flex-col p-4 bg-white rounded-xl shadow-lg border border-slate-100 transform hover:scale-105 transition-transform duration-200">
                                <span class="text-2xl sm:text-4xl font-bold text-indigo-600 tabular-nums"
                                    x-text="hours">00</span>
                                <span class="text-xs uppercase font-semibold text-slate-400 mt-1">Jam</span>
                            </div>
                            <div
                                class="flex flex-col p-4 bg-white rounded-xl shadow-lg border border-slate-100 transform hover:scale-105 transition-transform duration-200">
                                <span class="text-2xl sm:text-4xl font-bold text-indigo-600 tabular-nums"
                                    x-text="minutes">00</span>
                                <span class="text-xs uppercase font-semibold text-slate-400 mt-1">Menit</span>
                            </div>
                            <div
                                class="flex flex-col p-4 bg-white rounded-xl shadow-lg border border-slate-100 transform hover:scale-105 transition-transform duration-200">
                                <span class="text-2xl sm:text-4xl font-bold text-indigo-600 tabular-nums"
                                    x-text="seconds">00</span>
                                <span class="text-xs uppercase font-semibold text-slate-400 mt-1">Detik</span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 mt-6 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Hasil pemilihan akan ditampilkan otomatis setelah periode berakhir.
                        </p>
                    </div>
                </template>

                <!-- Calculating Results (Transition State) -->
                <template x-if="isCalculating">
                    <div class="bg-white border-2 border-indigo-200 rounded-xl p-8 max-w-lg mx-auto shadow-lg">
                        <div class="flex flex-col items-center">
                            <svg class="animate-spin h-12 w-12 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Menghitung Hasil...</h3>
                            <p class="text-sm text-slate-600">Mohon tunggu sebentar</p>
                        </div>
                    </div>
                </template>

                <!-- Election Results Leaderboard (When Closed) -->
                <template x-if="status === 'closed' && !isCalculating">
                    <div class="max-w-4xl mx-auto">
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-blue-50 border-2 border-indigo-200 rounded-2xl p-6 sm:p-8 mb-8 shadow-xl">
                            <div class="flex items-center justify-center gap-3 mb-4">
                                <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Hasil Pemilihan</h2>
                            </div>
                            <p class="text-center text-slate-700 mb-2">Pemilihan telah berakhir</p>
                            <p class="text-center text-lg font-semibold text-indigo-600">
                                Total Suara Terkumpul: <span class="text-2xl">{{ number_format($totalVotes) }}</span>
                            </p>
                        </div>

                        @if ($hasVotingEnded && $totalVotes > 0)
                            <!-- Leaderboard -->
                            <div class="space-y-3 sm:space-y-4">
                                @foreach ($candidates as $candidate)
                                    <div
                                        class="bg-white rounded-xl shadow-lg border-2 {{ $candidate->rank === 1 ? 'border-yellow-400 ring-2 sm:ring-4 ring-yellow-100' : 'border-slate-200' }} p-4 sm:p-6 transition-all hover:shadow-xl">
                                        <div class="flex items-start gap-3 sm:gap-4">
                                            <!-- Rank Badge -->
                                            <div class="flex-shrink-0">
                                                @if ($candidate->rank === 1)
                                                    <div class="relative">
                                                        <div
                                                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-lg">
                                                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-400"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-slate-100 flex items-center justify-center text-xl sm:text-2xl font-bold text-slate-600">
                                                        {{ $candidate->rank }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Candidate Info -->
                                            <div class="flex-1 min-w-0">
                                                <!-- Header with photo, name, and badge -->
                                                <div class="flex items-start gap-2 sm:gap-3 mb-3">
                                                    @if ($candidate->photo_path)
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($candidate->photo_path) }}"
                                                            alt="{{ $candidate->name }}"
                                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-indigo-200 flex-shrink-0">
                                                    @else
                                                        <div
                                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-400"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif

                                                    <div class="flex-1 min-w-0">
                                                        <h3
                                                            class="font-bold text-base sm:text-lg text-slate-900 {{ $candidate->rank === 1 ? 'text-yellow-700' : '' }} truncate">
                                                            {{ $candidate->name }}
                                                        </h3>
                                                        <p class="text-xs text-slate-500">Kandidat No.
                                                            {{ $candidate->order_number }}</p>

                                                        @if ($candidate->rank === 1)
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-300 mt-1">
                                                                🏆 PEMENANG
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Progress Bar -->
                                                <div class="mb-2 sm:mb-3">
                                                    <div class="flex items-center justify-between text-xs sm:text-sm mb-1">
                                                        <span class="font-semibold text-slate-700">Perolehan Suara</span>
                                                        <span
                                                            class="font-bold {{ $candidate->rank === 1 ? 'text-yellow-600' : 'text-indigo-600' }}">
                                                            {{ number_format($candidate->votes_count) }} Suara
                                                            ({{ $candidate->vote_percentage }}%)
                                                        </span>
                                                    </div>
                                                    <div
                                                        class="w-full bg-slate-200 rounded-full h-2.5 sm:h-3 overflow-hidden">
                                                        <div class="{{ $candidate->rank === 1 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : 'bg-gradient-to-r from-indigo-500 to-blue-500' }} h-full rounded-full transition-all duration-1000 ease-out"
                                                            style="width: {{ $candidate->vote_percentage }}%"></div>
                                                    </div>
                                                </div>

                                                <!-- Vision Preview -->
                                                <p class="text-xs sm:text-sm text-slate-600 line-clamp-2">
                                                    {!! strip_tags($candidate->vision) !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 text-center">
                                <p class="text-slate-600">Tidak ada data hasil pemilihan.</p>
                            </div>
                        @endif

                        <p class="text-center text-sm text-slate-500 mt-8">
                            Terima kasih atas partisipasi Anda dalam proses demokrasi ini! 🗳️
                        </p>
                    </div>
                </template>
            </div>

            <!-- Call to Action -->
            @auth
                <a href="{{ route('voting.index') }}"
                    class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                    Menuju Dashboard Voting
                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <div class="space-y-4">
                    <a id="login-button" href="{{ route('login') }}"
                        class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 w-full sm:w-auto">
                        Login untuk Memilih
                        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <p class="text-sm text-slate-500">Akses aman via Google Login diperlukan</p>
                </div>
            @endauth

        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-slate-50 border-y border-slate-200">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-center gap-8 md:gap-24">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm border border-slate-100 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Total Suara Masuk
                        </p>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($totalVotes) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm border border-slate-100 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Kandidat</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $candidates->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate Grid with Alpine.js Modal -->
    <div x-data="candidateModal()" class="bg-white py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Kenali Para Kandidat</h2>
                <p class="mt-4 text-lg text-slate-600">Pelajari rekam jejak, visi, dan misi mereka secara mendalam.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ($candidates as $candidate)
                    <div @if ($loop->first) id="candidate-card-1" @endif
                        class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-200 transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <!-- Photo -->
                        <div class="aspect-w-3 aspect-h-3 bg-slate-200 overflow-hidden relative h-72">
                            @if ($candidate->photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($candidate->photo_path) }}"
                                    alt="{{ $candidate->name }}"
                                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            <!-- Overlay Gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex-1 flex flex-col text-center">
                            <span
                                class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full mb-3 mx-auto">
                                No. Urut {{ $candidate->order_number }}
                            </span>
                            <h3
                                class="text-xl font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                {{ $candidate->name }}
                            </h3>
                            <p class="text-sm text-slate-500 mb-4 line-clamp-3">
                                {!! strip_tags($candidate->vision) !!}
                            </p>

                            <div class="mt-auto">
                                <button @if ($loop->first) id="candidate-detail-1" @endif
                                    @click="openModal({{ json_encode($candidate) }})"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-indigo-600 text-sm font-medium rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    Lihat Profil Lengkap
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Full Screen Modal Overlay -->
        <div x-show="isOpen" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true"
            x-cloak>
            <!-- Backdrop -->
            <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Panel -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.away="closeModal()"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl max-h-[90vh] flex flex-col">

                        <!-- Modal Header (Sticky) -->
                        <div class="bg-indigo-600 px-4 py-4 sm:px-6 flex justify-between items-center sticky top-0 z-20">
                            <h3 class="text-lg font-semibold leading-6 text-white" id="modal-title">
                                Profil Kandidat
                            </h3>
                            <button @click="closeModal()" class="text-indigo-200 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body (Scrollable) -->
                        <div class="px-4 py-6 sm:px-8 overflow-y-auto custom-scrollbar">
                            <template x-if="activeCandidate">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Left Column: Photo & Personal Info -->
                                    <div class="lg:col-span-1 space-y-6">
                                        <div
                                            class="aspect-w-3 aspect-h-4 rounded-xl overflow-hidden shadow-lg border border-slate-100">
                                            <template x-if="activeCandidate.photo_path">
                                                <img :src="'/storage/' + activeCandidate.photo_path"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!activeCandidate.photo_path">
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                                    No Photo</div>
                                            </template>
                                        </div>

                                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                            <h4 class="font-bold text-slate-900 text-lg mb-1"
                                                x-text="activeCandidate.name"></h4>
                                            <p class="text-indigo-600 font-medium text-sm mb-4"
                                                x-text="'Kandidat No. ' + activeCandidate.order_number"></p>

                                            <dl class="space-y-3 text-sm">
                                                <div x-show="activeCandidate.birth_place || activeCandidate.birth_date">
                                                    <dt class="text-slate-500 text-xs uppercase font-bold tracking-wider">
                                                        Tempat, Tanggal Lahir</dt>
                                                    <dd class="text-slate-800 mt-0.5">
                                                        <span x-text="activeCandidate.birth_place"></span>
                                                        <span
                                                            x-show="activeCandidate.birth_place && activeCandidate.birth_date">,
                                                        </span>
                                                        <span x-text="formatDate(activeCandidate.birth_date)"></span>
                                                    </dd>
                                                </div>

                                                <div x-show="activeCandidate.occupation">
                                                    <dt class="text-slate-500 text-xs uppercase font-bold tracking-wider">
                                                        Pekerjaan</dt>
                                                    <dd class="text-slate-800 mt-0.5" x-text="activeCandidate.occupation">
                                                    </dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>

                                    <!-- Right Column: Details Tabs/Sections -->
                                    <div class="lg:col-span-2 space-y-8">
                                        <!-- Visi & Misi -->
                                        <section>
                                            <div class="flex items-center gap-2 mb-4">
                                                <div class="h-8 w-1 bg-indigo-600 rounded-full"></div>
                                                <h4 class="text-2xl font-bold text-slate-900">Visi & Misi</h4>
                                            </div>
                                            <div class="prose prose-indigo prose-sm max-w-none bg-white">
                                                <div class="mb-4">
                                                    <strong class="text-indigo-700 block mb-1">Visi</strong>
                                                    <div x-html="activeCandidate.vision" class="text-slate-600"></div>
                                                </div>
                                                <div>
                                                    <strong class="text-indigo-700 block mb-1">Misi</strong>
                                                    <div x-html="activeCandidate.mission" class="text-slate-600"></div>
                                                </div>
                                            </div>
                                        </section>

                                        <hr class="border-slate-100">

                                        <!-- Education -->
                                        <section x-show="activeCandidate.education_history">
                                            <div class="flex items-center gap-2 mb-4">
                                                <div class="h-8 w-1 bg-pink-500 rounded-full"></div>
                                                <h4 class="text-xl font-bold text-slate-900">Riwayat Pendidikan</h4>
                                            </div>
                                            <div class="prose prose-sm max-w-none text-slate-600"
                                                x-html="activeCandidate.education_history"></div>
                                        </section>

                                        <!-- Organization -->
                                        <section x-show="activeCandidate.organization_experience">
                                            <div class="flex items-center gap-2 mb-4">
                                                <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
                                                <h4 class="text-xl font-bold text-slate-900">Pengalaman Organisasi</h4>
                                            </div>
                                            <div class="prose prose-sm max-w-none text-slate-600"
                                                x-html="activeCandidate.organization_experience"></div>
                                        </section>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                            @auth
                                <a href="{{ route('voting.index') }}"
                                    class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">
                                    Vote Kandidat Ini
                                </a>
                            @else
                                <a href="{{ route('filament.admin.auth.login') }}"
                                    class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">
                                    Login untuk Memilih
                                </a>
                            @endauth
                            <button type="button" @click="closeModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm mb-4">Sistem Voting Berintegritas &copy; {{ date('Y') }}. Hak cipta
                dilindungi.</p>
            <p class="text-gray-500 text-xs">Based on Laravel 12 & Filament v4</p>
        </div>
    </footer>
@endsection

@push('scripts')
    <script>
        function timer(endAt, startAt, hasVotingEnded) {
            return {
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                status: 'loading', // 'upcoming', 'active', 'closed'
                endTime: null,
                startTime: null,
                isCalculating: false,
                alreadyEnded: hasVotingEnded, // Flag from backend to prevent infinite reload

                init() {
                    if (!endAt) {
                        this.status = 'closed';
                        return;
                    }

                    // Parse ISO8601 strings with timezone offset (e.g., 2026-01-12T18:30:00+07:00)
                    // Browser will correctly interpret the timezone
                    this.endTime = new Date(endAt).getTime();
                    this.startTime = startAt ? new Date(startAt).getTime() : Date.now();

                    // Immediate check on page load
                    this.updateTimer();

                    // Only start interval if not already closed
                    if (this.status !== 'closed') {
                        setInterval(() => {
                            this.updateTimer();
                        }, 1000);
                    }
                },

                updateTimer() {
                    const now = Date.now();

                    // Check if voting hasn't started yet
                    if (this.startTime > now) {
                        this.status = 'upcoming';
                        const distanceToStart = this.startTime - now;
                        this.calculateTime(distanceToStart);
                    }
                    // Check if voting is active
                    else if (this.endTime > now) {
                        this.status = 'active';
                        const distanceToEnd = this.endTime - now;
                        this.calculateTime(distanceToEnd);
                    }
                    // Voting has just ended
                    else {
                        // Only trigger reload if countdown just expired (not already ended from backend)
                        if (this.status === 'active' && !this.alreadyEnded && !this.isCalculating) {
                            this.triggerResultsRefresh();
                        }

                        this.status = 'closed';
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                    }
                },

                triggerResultsRefresh() {
                    this.isCalculating = true;

                    // Show "Calculating..." for 2 seconds, then reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },

                calculateTime(distance) {
                    if (distance < 0) {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        return;
                    }

                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2,
                        '0');
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
                }
            }
        }

        function candidateModal() {
            return {
                isOpen: false,
                activeCandidate: null,

                openModal(candidate) {
                    this.activeCandidate = candidate;
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isOpen = false;
                    setTimeout(() => {
                        this.activeCandidate = null;
                    }, 300);
                    document.body.classList.remove('overflow-hidden');
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                }
            }
        }

        /* ==========================
           Driver.js Welcome Tour
           ========================== */
        // Dynamic loader for Driver.js (v1.x) and high-contrast textual fallback
        function loadDriverAssets(cb) {
            // Check for v1.x namespace (window.driver.js.driver)
            if (window.driver && window.driver.js) return cb();

            if (window._driverLoading) {
                window._driverLoadCallbacks = window._driverLoadCallbacks || [];
                window._driverLoadCallbacks.push(cb);
                return;
            }

            window._driverLoading = true;
            window._driverLoadCallbacks = [cb];

            const css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css';
            document.head.appendChild(css);

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js';
            script.onload = () => {
                window._driverLoading = false;
                (window._driverLoadCallbacks || []).forEach(fn => fn());
            };
            script.onerror = () => {
                window._driverLoading = false;
                (window._driverLoadCallbacks || []).forEach(fn => fn(new Error('Driver load failed')));
            };
            document.body.appendChild(script);
        }

        function showTextGuide(steps) {
            // Remove existing fallback if any
            const existing = document.getElementById('fallback-guide');
            if (existing) existing.remove();

            const overlay = document.createElement('div');
            overlay.id = 'fallback-guide';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');
            overlay.className = 'fixed inset-0 z-[60] flex items-center justify-center p-4';

            const backdrop = document.createElement('div');
            backdrop.className = 'absolute inset-0 bg-black/60';
            overlay.appendChild(backdrop);

            const box = document.createElement('div');
            box.className = 'relative z-10 max-w-2xl w-full bg-white rounded-xl p-6 shadow-2xl text-slate-900';
            box.style.fontSize = '1.125rem';

            const title = document.createElement('h2');
            title.className = 'text-xl font-bold mb-3';
            title.innerText = 'Panduan Singkat';
            box.appendChild(title);

            const desc = document.createElement('div');
            desc.id = 'fallback-guide-body';
            desc.className = 'text-lg text-slate-700 mb-4';
            box.appendChild(desc);

            const controls = document.createElement('div');
            controls.className = 'flex items-center justify-between gap-3';

            const prev = document.createElement('button');
            prev.className = 'px-4 py-2 rounded bg-slate-100 text-slate-900 font-semibold';
            prev.innerText = 'Kembali';

            const next = document.createElement('button');
            next.className = 'px-4 py-2 rounded bg-indigo-600 text-white font-semibold';
            next.innerText = 'Lanjut';

            const close = document.createElement('button');
            close.className = 'ml-2 px-4 py-2 rounded bg-white border border-slate-200 text-slate-900 font-semibold';
            close.innerText = 'Selesai';

            controls.appendChild(prev);
            controls.appendChild(next);
            controls.appendChild(close);

            box.appendChild(controls);
            overlay.appendChild(box);
            document.body.appendChild(overlay);

            let index = 0;

            function render() {
                const step = steps[index];
                desc.innerHTML =
                    `<strong>${step.popover?.title || ''}</strong><p class="mt-2">${step.popover?.description || ''}</p>`;
                prev.disabled = index === 0;
                if (index === steps.length - 1) next.innerText = 'Selesai';
                else next.innerText = 'Lanjut';
            }

            prev.addEventListener('click', () => {
                if (index > 0) {
                    index--;
                    render();
                }
            });
            next.addEventListener('click', () => {
                if (index < steps.length - 1) {
                    index++;
                    render();
                } else {
                    overlay.remove();
                }
            });
            close.addEventListener('click', () => overlay.remove());

            render();
            next.focus();
        }

        function startWelcomeTour() {
            const steps = [];

            if (document.querySelector('#voting-timer')) {
                steps.push({
                    element: '#voting-timer',
                    popover: {
                        title: 'Waktu Pemilihan',
                        description: 'Waktu Pemilihan. Pastikan Bapak/Ibu mencoblos sebelum waktu ini habis.',
                        side: 'bottom',
                        align: 'center'
                    }
                });
            }

            if (document.querySelector('#login-button')) {
                steps.push({
                    element: '#login-button',
                    popover: {
                        title: 'Tombol Masuk',
                        description: 'Tombol Masuk. Klik di sini untuk mulai masuk ke bilik suara.',
                        side: 'bottom',
                        align: 'center'
                    }
                });
            }

            if (document.querySelector('#candidate-card-1')) {
                steps.push({
                    element: '#candidate-card-1',
                    popover: {
                        title: 'Daftar Calon',
                        description: 'Daftar Calon. Gulir ke bawah untuk melihat semua kandidat yang tersedia.',
                        side: 'top',
                        align: 'center'
                    }
                });
            }

            if (document.querySelector('#candidate-detail-1')) {
                steps.push({
                    element: '#candidate-detail-1',
                    popover: {
                        title: 'Detail Kandidat',
                        description: 'Detail Kandidat. Klik tombol ini untuk membaca visi & misi selengkapnya.',
                        side: 'bottom',
                        align: 'center'
                    }
                });
            }

            if (steps.length === 0) return;

            // Pointer helper functions
            function createPointer() {
                let el = document.getElementById('driver-pointer');
                if (el) return el;
                el = document.createElement('div');
                el.id = 'driver-pointer';
                el.setAttribute('aria-hidden', 'true');
                el.style.position = 'fixed';
                el.style.zIndex = '100002'; // Higher than driver popover (usually 100000)
                el.style.pointerEvents = 'none';
                el.style.transition = 'all 0.3s ease';
                el.innerHTML =
                    `<svg width="56" height="56" viewBox="0 0 24 24" fill="none" class="drop-shadow-xl"><path d="M3 3l18 12-7 1 1 7-12-18z" fill="#4F46E5" stroke="white" stroke-width="1.5"/></svg>`;
                document.body.appendChild(el);
                return el;
            }

            function removePointer() {
                const p = document.getElementById('driver-pointer');
                if (p) p.remove();
            }

            function showPointerFor(element) {
                if (!element) return removePointer();

                // If element is a string selector, select it
                const target = typeof element === 'string' ? document.querySelector(element) : element;
                if (!target) return removePointer();

                const rect = target.getBoundingClientRect();
                const pointer = createPointer();

                // Calculate position: Point to the element
                // Default: Point from bottom-right (cursor style)
                let left = rect.right - 20;
                let top = rect.bottom - 20;

                // Adjust if off-screen
                if (left > window.innerWidth - 60) left = rect.left + rect.width / 2;
                if (top > window.innerHeight - 60) top = rect.top + rect.height / 2;

                pointer.style.left = `${left}px`;
                pointer.style.top = `${top}px`;

                // Simple bounce
                pointer.animate([{
                        transform: 'translate(0, 0)'
                    },
                    {
                        transform: 'translate(-5px, -5px)'
                    }
                ], {
                    duration: 800,
                    iterations: Infinity,
                    direction: 'alternate',
                    easing: 'ease-in-out'
                });
            }

            loadDriverAssets(function(err) {
                if (err || !window.driver?.js?.driver) {
                    showTextGuide(steps);
                    return;
                }

                try {
                    const driverObj = window.driver.js.driver({
                        animate: true,
                        allowClose: true,
                        overlayClickNext: true,
                        doneBtnText: 'Selesai',
                        nextBtnText: 'Lanjut',
                        prevBtnText: 'Kembali',
                        popoverClass: 'driver-theme-accessible', // Custom class if needed
                        steps: steps,
                        onHighlightStarted: (element) => {
                            // Driver.js v1 passes the DOM element to this hook
                            if (element) showPointerFor(element);
                        },
                        onDeselected: () => {
                            removePointer();
                        },
                        onDestroyed: () => {
                            removePointer();
                        }
                    });

                    driverObj.drive();

                } catch (e) {
                    console.error('Driver start failed', e);
                    showTextGuide(steps);
                }
            });
        }

        // Auto-start welcome tour on first visit
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const seen = localStorage.getItem('welcomeTourSeen');
                if (!seen) {
                    // Retry mechanism for CDN load
                    let attempts = 0;
                    const interval = setInterval(() => {
                        if (typeof window.driver !== 'undefined') {
                            clearInterval(interval);
                            setTimeout(() => startWelcomeTour(), 500);
                            localStorage.setItem('welcomeTourSeen', '1');
                        } else {
                            attempts++;
                            // Try calling loadDriverAssets explicitly if not loaded yet
                            if (attempts === 1) loadDriverAssets(() => {});

                            if (attempts > 30) { // 6s timeout
                                clearInterval(interval);
                            }
                        }
                    }, 200);
                }
            } catch (e) {}
        });

        // Manual Trigger
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('start-welcome-tour');
            if (btn) {
                btn.addEventListener('click', function() {
                    // Reset seen state if user manually clicks, so they can see it again?
                    // Or just run it.
                    startWelcomeTour();
                });
            }
        });
    </script>
@endpush
