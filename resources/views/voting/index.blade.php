@extends('layouts.app')

@section('title', config('app.name', 'Secure Voting') . ' - Bilik Suara')

@section('navbar-actions')
    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
        @if (Auth::user()->avatar)
            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="h-8 w-8 rounded-full bg-slate-200 object-cover">
        @else
            <div
                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
        @endif

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
    </div>
@endsection

@section('content')
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3 max-w-3xl mx-auto">
                    <svg class="h-6 w-6 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Gagal Validasi</h3>
                        <div class="mt-1 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3 max-w-3xl mx-auto">
                    <svg class="h-6 w-6 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Berhasil</h3>
                        <div class="mt-1 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif


            @if ($hasVoted)
                <!-- Already Voted State -->
                <div class="flex flex-col items-center justify-center py-16 text-center animate-fade-in-up">
                    <div class="h-24 w-24 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Pemilihan Selesai!</h2>
                    <p class="text-lg text-slate-600 max-w-lg mb-8">
                        Terima kasih telah berpartisipasi. Suara Anda telah direkam secara aman dan dianonymkan dalam sistem
                        kami.
                    </p>
                    <div class="p-6 bg-white border border-slate-200 rounded-xl shadow-sm w-full max-w-md">
                        <p class="text-sm text-slate-500 uppercase tracking-wide font-semibold mb-2">Status Verifikasi
                        </p>
                        <div class="flex items-center justify-center gap-2 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <span class="font-medium">Terverifikasi & Tersimpan</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <a href="{{ route('welcome') }}"
                                class="text-sm text-slate-600 hover:text-indigo-600 underline">Kembali ke Halaman Depan</a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Voting Interface -->
                <div x-data="votingApp()" x-init="initData()">
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">Berikan Suara Anda</h1>
                        <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                            Pilih kandidat pilihan Anda di bawah ini. Harap tinjau pilihan Anda dengan cermat, karena suara
                            Anda
                            tidak dapat diubah setelah dikirim.
                        </p>
                        <p class="text-sm text-slate-400 mt-2 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            Sesi Aman Aktif
                        </p>
                    </div>

                    <!-- Candidate Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
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
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
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

                                    <div class="mt-auto space-y-3">
                                        <button @click="openProfileModal({{ json_encode($candidate) }})"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Lihat Profil
                                        </button>

                                        <button
                                            @click="openConfirmModal({{ $candidate->id }}, '{{ addslashes($candidate->name) }}')"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                            Pilih Kandidat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Profile Modal -->
                    <div x-show="isProfileOpen" class="relative z-[100]" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true" x-cloak>
                        <!-- Backdrop -->
                        <div x-show="isProfileOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"></div>

                        <!-- Modal Panel -->
                        <div class="fixed inset-0 z-10 overflow-y-auto">
                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div x-show="isProfileOpen" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    @click.away="closeProfileModal()"
                                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl max-h-[90vh] flex flex-col">

                                    <!-- Modal Header (Sticky) -->
                                    <div
                                        class="bg-indigo-600 px-4 py-4 sm:px-6 flex justify-between items-center sticky top-0 z-20">
                                        <h3 class="text-lg font-semibold leading-6 text-white" id="modal-title">
                                            Profil Kandidat
                                        </h3>
                                        <button @click="closeProfileModal()"
                                            class="text-indigo-200 hover:text-white transition-colors">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
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
                                                            <div
                                                                x-show="activeCandidate.birth_place || activeCandidate.birth_date">
                                                                <dt
                                                                    class="text-slate-500 text-xs uppercase font-bold tracking-wider">
                                                                    Tempat, Tanggal Lahir</dt>
                                                                <dd class="text-slate-800 mt-0.5">
                                                                    <span x-text="activeCandidate.birth_place"></span>
                                                                    <span
                                                                        x-show="activeCandidate.birth_place && activeCandidate.birth_date">,
                                                                    </span>
                                                                    <span
                                                                        x-text="formatDate(activeCandidate.birth_date)"></span>
                                                                </dd>
                                                            </div>

                                                            <div x-show="activeCandidate.occupation">
                                                                <dt
                                                                    class="text-slate-500 text-xs uppercase font-bold tracking-wider">
                                                                    Pekerjaan</dt>
                                                                <dd class="text-slate-800 mt-0.5"
                                                                    x-text="activeCandidate.occupation"></dd>
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
                                                                <div x-html="activeCandidate.vision"
                                                                    class="text-slate-600"></div>
                                                            </div>
                                                            <div>
                                                                <strong class="text-indigo-700 block mb-1">Misi</strong>
                                                                <div x-html="activeCandidate.mission"
                                                                    class="text-slate-600"></div>
                                                            </div>
                                                        </div>
                                                    </section>

                                                    <hr class="border-slate-100">

                                                    <!-- Education -->
                                                    <section x-show="activeCandidate.education_history">
                                                        <div class="flex items-center gap-2 mb-4">
                                                            <div class="h-8 w-1 bg-pink-500 rounded-full"></div>
                                                            <h4 class="text-xl font-bold text-slate-900">Riwayat Pendidikan
                                                            </h4>
                                                        </div>
                                                        <div class="prose prose-sm max-w-none text-slate-600"
                                                            x-html="activeCandidate.education_history"></div>
                                                    </section>

                                                    <!-- Organization -->
                                                    <section x-show="activeCandidate.organization_experience">
                                                        <div class="flex items-center gap-2 mb-4">
                                                            <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
                                                            <h4 class="text-xl font-bold text-slate-900">Pengalaman
                                                                Organisasi</h4>
                                                        </div>
                                                        <div class="prose prose-sm max-w-none text-slate-600"
                                                            x-html="activeCandidate.organization_experience"></div>
                                                    </section>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div
                                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                                        <button type="button"
                                            @click="closeProfileModal(); openConfirmModal(activeCandidate.id, activeCandidate.name)"
                                            class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">
                                            Pilih Kandidat Ini
                                        </button>
                                        <button type="button" @click="closeProfileModal()"
                                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Modal -->
                    <div x-show="isModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title"
                        role="dialog" aria-modal="true" x-cloak>
                        <!-- Background backdrop -->
                        <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"></div>

                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                @click.away="closeModal()"
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                            <h3 class="text-xl font-semibold leading-6 text-slate-900" id="modal-title">
                                                Konfirmasi Suara Anda</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-slate-500">
                                                    Anda akan memberikan suara untuk <span class="font-bold text-slate-800"
                                                        x-text="selectedCandidateName"></span>.
                                                </p>
                                                <p class="text-sm text-slate-500 mt-2">
                                                    Tindakan ini <span class="font-bold text-red-600">tidak dapat
                                                        dibatalkan</span>. Pastikan ini adalah keputusan akhir Anda.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <form action="{{ route('vote.store') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="candidate_id" :value="selectedCandidateId">
                                        <input type="hidden" name="device_hash" :value="deviceHash">

                                        <button type="submit"
                                            class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">
                                            Konfirmasi Suara
                                        </button>
                                    </form>
                                    <button type="button" @click="closeModal()"
                                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function votingApp() {
            return {
                isModalOpen: false,
                isProfileOpen: false,
                selectedCandidateId: null,
                selectedCandidateName: '',
                activeCandidate: null,
                deviceHash: '',

                initData() {
                    this.deviceHash = this.getDeviceHash();
                    console.log('Device Hash:', this.deviceHash);
                },

                getDeviceHash() {
                    let hash = localStorage.getItem('secure_vote_device_id');
                    if (!hash) {
                        // Generate a simple persistent ID if not present
                        const randomPart = Math.random().toString(36).substring(2, 15) + Math.random().toString(36)
                            .substring(2, 15);
                        const timestamp = new Date().getTime();
                        hash = `dev_${timestamp}_${randomPart}`;
                        localStorage.setItem('secure_vote_device_id', hash);
                    }
                    return hash;
                },

                openProfileModal(candidate) {
                    this.activeCandidate = candidate;
                    this.isProfileOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeProfileModal() {
                    this.isProfileOpen = false;
                    setTimeout(() => {
                        this.activeCandidate = null;
                    }, 300);
                    document.body.classList.remove('overflow-hidden');
                },

                openConfirmModal(id, name) {
                    this.selectedCandidateId = id;
                    this.selectedCandidateName = name;
                    this.isModalOpen = true;
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.selectedCandidateId = null;
                    this.selectedCandidateName = '';
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
