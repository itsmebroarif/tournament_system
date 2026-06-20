<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ tab: 'queue' }" class="space-y-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex gap-6">
                        <button @click="tab = 'queue'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'queue', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'queue' }" class="pb-3 text-sm font-medium border-b-2 transition-colors">
                            Antrian
                        </button>
                        <button @click="tab = 'bracket'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'bracket', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'bracket' }" class="pb-3 text-sm font-medium border-b-2 transition-colors">
                            Bracket
                        </button>
                        <button @click="tab = 'certificates'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': tab === 'certificates', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'certificates' }" class="pb-3 text-sm font-medium border-b-2 transition-colors">
                            Sertifikat
                        </button>
                    </nav>
                </div>

                <div x-show="tab === 'queue'" x-cloak>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <livewire:admin.queue-list />
                    </div>
                </div>

                <div x-show="tab === 'bracket'" x-cloak>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <livewire:admin.tournament-bracket />
                    </div>
                </div>

                <div x-show="tab === 'certificates'" x-cloak>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <livewire:admin.certificate-manager />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
