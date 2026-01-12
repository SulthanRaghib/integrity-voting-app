@extends('layouts.app')

@section('navbar-actions')
    @auth
        <a href="{{ route('voting.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
            Dasbor
        </a>
    @else
        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
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
            <div x-data="timer('{{ $settings?->end_at?->toIso8601String() }}', '{{ $settings?->start_at?->toIso8601String() }}')" x-init="init()" class="w-full max-w-3xl mx-auto mb-12 min-h-[160px]">

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
                </template>
                <template x-if="status === 'closed'">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 max-w-lg mx-auto">
                        <p class="text-slate-600 font-medium">Periode pemilihan telah berakhir.</p>
                        <p class="text-sm text-slate-500 mt-2">Hasil akan segera diumumkan.</p>
                    </div>
                </template>
            </div>

            <!-- Call to Action -->
            @auth
                <a href="{{ route('voting.index') }}"
                    class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                    Menuju Dashboard Voting
                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <div class="space-y-4">
                    <a href="{{ route('login') }}"
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
                    <div
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
                                <button @click="openModal({{ json_encode($candidate) }})"
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
        function timer(endAt, startAt) {
            return {
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                status: 'loading', // 'upcoming', 'active', 'closed'
                endTime: null,
                startTime: null,

                init() {
                    if (!endAt) {
                        this.status = 'closed';
                        return;
                    }

                    this.endTime = new Date(endAt).getTime();
                    this.startTime = startAt ? new Date(startAt).getTime() : new Date().getTime();

                    this.updateTimer();
                    setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                },

                updateTimer() {
                    const now = new Date().getTime();

                    if (this.startTime > now) {
                        this.status = 'upcoming';
                        const distanceToStart = this.startTime - now;
                        this.calculateTime(distanceToStart);

                    } else if (this.endTime > now) {
                        this.status = 'active';
                        const distanceToEnd = this.endTime - now;
                        this.calculateTime(distanceToEnd);
                    } else {
                        this.status = 'closed';
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        return;
                    }
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
    </script>
@endpush
