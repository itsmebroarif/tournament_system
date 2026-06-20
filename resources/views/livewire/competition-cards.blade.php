<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($competitions as $competition)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group">
            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center h-40">
                @if($competition->svg_content)
                    {!! $competition->svg_content !!}
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                @endif
            </div>
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ $competition->name }}</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $competition->age_category === 'anak-anak' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : '' }}
                        {{ $competition->age_category === 'remaja' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : '' }}
                        {{ $competition->age_category === 'dewasa' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : '' }}">
                        {{ ucfirst($competition->age_category) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $competition->type === 'individu' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300' }}">
                        {{ $competition->type === 'individu' ? 'Individu' : 'Tim' }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Belum ada kompetisi tersedia.</p>
        </div>
    @endforelse
</div>
