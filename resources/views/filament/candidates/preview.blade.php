<div class="p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                @if ($candidate->photo_path)
                    <img src="{{ Storage::disk('public')->url($candidate->photo_path) }}" alt="{{ $candidate->name }}"
                        class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-slate-100 flex items-center justify-center text-slate-400">No photo</div>
                @endif
            </div>

            <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                <h3 class="font-bold text-lg text-slate-900">{{ $candidate->name }}</h3>
                <p class="text-sm text-indigo-600">Kandidat No. {{ $candidate->order_number }}</p>
                @if ($candidate->occupation)
                    <p class="text-sm text-slate-600 mt-2">{{ $candidate->occupation }}</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <section>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Visi</h4>
                <div class="prose max-w-none text-slate-700" style="min-height: 48px">{!! $candidate->vision ?? '<p><em>Tidak ada visi</em></p>' !!}</div>
            </section>

            <section>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Misi</h4>
                <div class="prose max-w-none text-slate-700" style="min-height: 48px">{!! $candidate->mission ?? '<p><em>Tidak ada misi</em></p>' !!}</div>
            </section>

            <section>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Riwayat Pendidikan</h4>
                <div class="prose max-w-none text-slate-700" style="min-height: 48px">{!! $candidate->education_history ?? '<p><em>Tidak ada riwayat pendidikan</em></p>' !!}</div>
            </section>

            <section>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Pengalaman Organisasi</h4>
                <div class="prose max-w-none text-slate-700" style="min-height: 48px">{!! $candidate->organization_experience ?? '<p><em>Tidak ada pengalaman organisasi</em></p>' !!}</div>
            </section>
        </div>
    </div>
</div>
