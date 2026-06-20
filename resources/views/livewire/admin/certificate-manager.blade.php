<div>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sertifikat</h3>
        <div class="flex items-center gap-3">
            <select wire:model.live="competition_id" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Kompetisi</option>
                @foreach($competitions as $comp)
                    <option value="{{ $comp->id }}">{{ $comp->name }} ({{ $comp->registrations_count }})</option>
                @endforeach
            </select>
            <select wire:model.live="rank_filter" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Semua Peringkat</option>
                <option value="juara_1">Juara 1</option>
                <option value="juara_2">Juara 2</option>
                <option value="juara_3">Juara 3</option>
            </select>
            <button wire:click="generateAll" wire:loading.attr="disabled" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors disabled:opacity-50">
                <span wire:loading.remove>Generate All</span>
                <span wire:loading>Generating...</span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400 font-medium">Nama</th>
                    <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400 font-medium">Kompetisi</th>
                    <th class="text-left py-3 px-4 text-gray-600 dark:text-gray-400 font-medium">Peringkat</th>
                    <th class="text-right py-3 px-4 text-gray-600 dark:text-gray-400 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/25">
                        <td class="py-3 px-4 text-gray-900 dark:text-white">{{ $reg->participant->name }}</td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ $reg->competition->name }}</td>
                        <td class="py-3 px-4">
                            @if($reg->rank)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $reg->rank === 1 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $reg->rank === 2 ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $reg->rank === 3 ? 'bg-orange-100 text-orange-800' : '' }}">
                                    Juara {{ $reg->rank }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($reg->certificate_path)
                                <a href="{{ Storage::url($reg->certificate_path) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Download
                                </a>
                            @else
                                <div class="flex gap-1">
                                    @foreach(['participant', 'juara_1', 'juara_2', 'juara_3'] as $rank)
                                        <button wire:click="generateForRegistration({{ $reg->id }}, '{{ $rank }}')" class="text-xs px-2 py-1 rounded {{ $rank === 'juara_1' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : ($rank === 'juara_2' ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : ($rank === 'juara_3' ? 'bg-orange-100 text-orange-800 hover:bg-orange-200' : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200')) }}">
                                            {{ str_replace('_', ' ', ucfirst($rank)) }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 dark:text-gray-400">Belum ada peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
