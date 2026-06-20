<div>
    <div class="flex items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tournament Bracket</h3>
        <select wire:model.live="competition_id" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">Pilih Kompetisi Tim</option>
            @foreach($teamCompetitions as $comp)
                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
            @endforeach
        </select>
    </div>

    @if($competition_id && $bracket['registrations']->count() >= 2)
        @if($bracket['matches']->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400 mb-4">Bracket belum di-generate. {{ $bracket['registrations']->count() }} tim terdaftar.</p>
                <button wire:click="generateBracket" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Generate Bracket</button>
            </div>
        @else
            <div class="overflow-x-auto">
                @php $maxRound = $bracket['max_round']; @endphp
                <div class="flex gap-8 min-w-max">
                    @foreach(range(1, $maxRound) as $round)
                        <div class="flex flex-col gap-4 min-w-[200px]">
                            <div class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                @if($round === $maxRound)
                                    Final
                                @elseif($round === $maxRound - 1)
                                    Semi Final
                                @else
                                    Round {{ $round }}
                                @endif
                            </div>
                            @php $roundMatches = $bracket['matches']->where('round', $round); @endphp
                            @foreach($roundMatches as $match)
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 shadow-sm">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $match->winner_registration_id && $match->winner_registration_id === $match->team_a_registration_id ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                                <span class="text-sm truncate {{ $match->winner_registration_id && $match->winner_registration_id === $match->team_a_registration_id ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                                    {{ $match->teamA?->participant?->name ?? $match->teamA?->team_name ?? 'TBD' }}
                                                </span>
                                            </div>
                                            @if($match->team_a_registration_id && $match->team_b_registration_id && !$match->winner_registration_id)
                                                <input type="number" wire:model.live="scores.{{ $match->id }}.a" class="w-12 text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-center" placeholder="0">
                                            @endif
                                        </div>
                                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $match->winner_registration_id && $match->winner_registration_id === $match->team_b_registration_id ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                                <span class="text-sm truncate {{ $match->winner_registration_id && $match->winner_registration_id === $match->team_b_registration_id ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
                                                    {{ $match->teamB?->participant?->name ?? $match->teamB?->team_name ?? ($match->team_b_registration_id ? 'Bye' : 'TBD') }}
                                                </span>
                                            </div>
                                            @if($match->team_a_registration_id && $match->team_b_registration_id && !$match->winner_registration_id)
                                                <input type="number" wire:model.live="scores.{{ $match->id }}.b" class="w-12 text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-center" placeholder="0">
                                            @endif
                                        </div>
                                    </div>
                                    @if($match->winner_registration_id)
                                        <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">
                                                Winner: {{ $match->winner?->participant?->name ?? $match->winner?->team_name ?? '-' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @elseif($competition_id)
        <p class="text-center py-8 text-gray-500 dark:text-gray-400">Minimal 2 tim diperlukan untuk membuat bracket.</p>
    @else
        <p class="text-center py-8 text-gray-500 dark:text-gray-400">Pilih kompetisi tim untuk melihat bracket.</p>
    @endif
</div>
