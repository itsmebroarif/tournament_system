<div wire:poll.10s>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Antrian Peserta</h3>
        <select wire:model.live="competition_filter" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Semua Kompetisi</option>
            @foreach($competitions as $comp)
                <option value="{{ $comp->id }}">{{ $comp->name }} ({{ $comp->registrations_count }})</option>
            @endforeach
        </select>
    </div>

    @forelse($registrations as $compName => $group)
        <div class="mb-6 last:mb-0">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ $compName }} <span class="text-gray-400">({{ $group->count() }})</span></h4>
            <div class="space-y-2">
                @foreach($group as $reg)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ substr($reg->participant->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reg->participant->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $reg->team_name ? 'Tim: ' . $reg->team_name . ' (' . $reg->teamMembers->count() + 1 . ' anggota)' : 'Individu' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $reg->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-center py-8 text-gray-500 dark:text-gray-400">Belum ada peserta terdaftar.</p>
    @endforelse
</div>
