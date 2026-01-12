<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Secure E-Voting</h1>
            <p class="text-gray-600 mt-2">Integrity Protected System</p>
            @auth
                <div class="mt-4">
                    <span class="text-sm font-medium">Logged in as: {{ auth()->user()->name }}</span>
                </div>
            @endauth
        </header>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($hasVoted)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative text-center">
                <h2 class="text-xl font-bold">Thank you for voting!</h2>
                <p>Your vote has been recorded securely.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($candidates as $candidate)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                        @if ($candidate->photo_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($candidate->photo_path) }}"
                                alt="{{ $candidate->name }}" class="w-full h-64 object-cover">
                        @else
                            <div
                                class="w-full h-64 bg-gray-300 flex items-center justify-center text-gray-500 font-bold">
                                No Photo</div>
                        @endif
                        <div class="p-6 flex-1 flex flex-col">
                            <h2 class="text-2xl font-bold mb-2">{{ $candidate->name }}</h2>
                            <div class="text-gray-600 text-sm mb-4 prose">
                                <h3 class="font-bold">Vision:</h3>
                                <div class="mb-2">{!! $candidate->vision !!}</div>
                                <h3 class="font-bold">Mission:</h3>
                                <div>{!! $candidate->mission !!}</div>
                            </div>

                            <div class="mt-auto">
                                <form action="{{ route('voting.store') }}" method="POST" class="vote-form"
                                    onsubmit="return confirm('Are you sure you want to vote for {{ $candidate->name }}? This cannot be undone.');">
                                    @csrf
                                    <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                    <input type="hidden" name="device_hash" class="device-hash-input">

                                    <button type="submit"
                                        class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                        Vote for {{ $candidate->name }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Init FingerprintJS
        document.addEventListener('DOMContentLoaded', () => {
            (async () => {
                try {
                    const fp = await FingerprintJS.load();
                    const result = await fp.get();
                    const visitorId = result.visitorId;

                    // Inject into all forms
                    document.querySelectorAll('.device-hash-input').forEach(input => {
                        input.value = visitorId;
                    });

                    // Enable buttons
                    document.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.disabled = false;
                    });

                    console.log('Integrity Check: Device Fingerprint Loaded');
                } catch (e) {
                    console.error('Fingerprint failed', e);
                    alert('Security check failed. Note: Ad-blockers might prevent secure voting.');
                }
            })();
        });
    </script>
</body>

</html>
