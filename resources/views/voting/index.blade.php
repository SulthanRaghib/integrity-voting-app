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
                                class="relative bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 overflow-hidden flex flex-col group {{ $loop->index == 0 ? 'ring-2 ring-transparent' : '' }}">
                                <!-- Added ring structure possibility -->

                                <!-- Selection Indicator (Only Visual in this state) -->
                                <div
                                    class="absolute top-4 right-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span
                                        class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Kandidat
                                        #{{ $candidate->order_number }}</span>
                                </div>

                                <div class="aspect-w-4 aspect-h-3 bg-slate-100 h-64 overflow-hidden">
                                    @if ($candidate->photo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($candidate->photo_path) }}"
                                            alt="{{ $candidate->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6 flex-1 flex flex-col">
                                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $candidate->name }}</h3>

                                    <div class="flex-grow">
                                        @if ($candidate->vision)
                                            <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                                                {{ $candidate->vision }}</p>
                                        @else
                                            <p class="text-slate-400 text-sm italic mb-4">Tidak ada visi misi yang
                                                diberikan.
                                            </p>
                                        @endif
                                    </div>

                                    <button type="button"
                                        @click="openConfirmModal({{ $candidate->id }}, '{{ addslashes($candidate->name) }}')"
                                        class="w-full mt-4 py-3 px-4 bg-white border-2 border-indigo-600 text-indigo-700 font-bold rounded-xl hover:bg-indigo-600 hover:text-white transition-colors flex items-center justify-center gap-2 group-hover:shadow-md">
                                        Pilih Kandidat
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
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
                selectedCandidateId: null,
                selectedCandidateName: '',
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

                openConfirmModal(id, name) {
                    this.selectedCandidateId = id;
                    this.selectedCandidateName = name;
                    this.isModalOpen = true;
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.selectedCandidateId = null;
                    this.selectedCandidateName = '';
                }
            }
        }
    </script>
@endpush
