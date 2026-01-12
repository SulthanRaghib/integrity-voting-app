<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-50 to-slate-100">
    <style>
        /* 🚫 Neutralize Filament's default login wrapper for this page only */
        .fi-simple-main.fi-width-lg {
            max-width: none !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            display: block !important;
        }

        .fi-simple-main.fi-width-lg>* {
            max-width: none !important;
            width: 100% !important;
        }

        /* Custom Login Button Styles */
        .login-btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background-color: #4F46E5;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-btn:hover:not(:disabled) {
            background-color: #4338CA;
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .login-btn-loading {
            align-items: center;
            justify-content: center;
            gap: 8px;
            line-height: 1;
        }

        .spinner {
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div
        class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-900/5 flex flex-col-reverse md:flex-row">

        <!-- Left Side (Mobile Bottom): Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12">
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-slate-800">SecureVote</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Login Admin</h2>
                <p class="mt-2 text-slate-600 text-sm">Masuk ke panel admin untuk mengelola voting.</p>
                <a href="/" class="inline-block mt-3 text-xs font-medium text-indigo-600 hover:text-indigo-500">←
                    Kembali ke Beranda</a>
            </div>

            <form wire:submit="authenticate" class="space-y-5">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-800 flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </p>
                    </div>
                @endif

                <div>
                    <label for="data.email" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Email Address
                    </label>
                    <input type="email" wire:model.live="data.email" id="data.email"
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3 transition-all"
                        placeholder="admin@gmail.com" autocomplete="email">
                    @error('data.email')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="data.password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" wire:model.live="data.password"
                            id="data.password"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm py-2.5 px-3 pr-10 transition-all"
                            placeholder="••••••••" autocomplete="current-password">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.057 10.057 0 01-2.106 5.493l2.564 2.564L21 21.05 3.39 3.44" />
                            </svg>
                        </button>
                    </div>
                    @error('data.password')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" wire:model="data.remember"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition-all" />
                        <span class="ml-2 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Ingat
                            Saya</span>
                    </label>
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="authenticate" class="login-btn">
                    <span wire:loading.remove wire:target="authenticate">Login Panel</span>
                    <span wire:loading.flex wire:target="authenticate" class="login-btn-loading">
                        <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>
        </div>

        <!-- Right Side (Mobile Top): Voter Info -->
        <div
            class="w-full md:w-1/2 bg-gradient-to-br from-indigo-50 to-blue-50 p-8 sm:p-12 border-b md:border-b-0 md:border-l border-slate-200 flex flex-col justify-center">

            <div class="mb-8">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-blue-100 text-blue-600 mb-5 ring-4 ring-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Peserta Voting?</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Untuk memberikan suara dalam pemilihan, silahkan masuk menggunakan akun Google Anda yang telah
                    terverifikasi.
                </p>
            </div>

            <a href="{{ route('auth.google') }}"
                class="flex items-center justify-center gap-3 w-full px-5 py-3.5 bg-white border-2 border-slate-200 rounded-xl shadow-sm text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:shadow-md transition-all duration-200 group">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                <span class="font-semibold">Masuk dengan Google</span>
                <svg class="w-4 h-4 opacity-0 -ml-2 group-hover:opacity-100 group-hover:ml-0 transition-all"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <div class="mt-6 bg-blue-100/50 rounded-lg p-4 border border-blue-200">
                <p class="text-xs text-blue-900 flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="leading-relaxed">
                        Pastikan email Google Anda sudah terdaftar sebagai pemilih sah dalam sistem.
                    </span>
                </p>
            </div>

        </div>
    </div>
</div>
