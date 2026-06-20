<div wire:poll.5s class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Participant Lounge</h2>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            {{ $count }} registered
        </span>
    </div>
    @if($participants->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-8">Belum ada peserta yang mendaftar.</p>
    @else
        <div class="space-y-2 max-h-96 overflow-y-auto">
            @foreach($participants as $participant)
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ substr($participant->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $participant->name }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
