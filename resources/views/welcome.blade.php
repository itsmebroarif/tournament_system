<x-guest-layout>
    <div class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">17an Competition Management</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">Daftar dan ikuti perlombaan Hari Kemerdekaan Indonesia</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Kompetisi</h2>
                    <livewire:competition-cards />
                </div>
                <div class="lg:col-span-1">
                    <livewire:lounge />
                </div>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('participant.register') }}" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
