<div class="max-w-2xl mx-auto">
    @if($submitted)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pendaftaran Berhasil!</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Terima kasih {{ $name }}, data kamu sudah tercatat.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Kembali ke Beranda</a>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Pendaftaran Peserta</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">Langkah {{ $step }} dari 5</span>
            </div>

            <div class="flex gap-2 mb-8">
                @foreach(range(1, 5) as $s)
                    <div class="flex-1 h-2 rounded-full {{ $s <= $step ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                @endforeach
            </div>

            @if($step === 1)
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
                        <select wire:model="gender" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                        <input type="date" wire:model="birth_date" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @if($birth_date)
                        <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Usia: <strong>{{ $age }} tahun</strong> — Kategori: <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $age_category === 'anak-anak' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $age_category === 'remaja' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $age_category === 'dewasa' ? 'bg-red-100 text-red-800' : '' }}">{{ ucfirst($age_category) }}</span></p>
                        </div>
                    @endif
                </div>
            @endif

            @if($step === 2)
                <div class="space-y-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Isi minimal salah satu kontak (No. HP atau Email).</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. HP</label>
                        <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" wire:model="email" placeholder="nama@email.com" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Social Media (Opsional)</label>
                        <input type="text" wire:model="social_media" placeholder="IG: @username" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            @endif

            @if($step === 3)
                <div class="space-y-4">
                    @if($available_competitions->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Tidak ada kompetisi yang tersedia untuk kategori usia {{ $age_category }}.</p>
                    @else
                        @foreach($available_competitions as $comp)
                            <label wire:key="comp-{{ $comp->id }}" class="flex items-center gap-4 p-4 rounded-lg border-2 cursor-pointer transition-colors
                                {{ $competition_id === $comp->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <input type="radio" wire:model="competition_id" value="{{ $comp->id }}" class="text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $comp->name }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                                        {{ $comp->type === 'individu' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $comp->type === 'individu' ? 'Individu' : 'Tim' }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                        @error('competition_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
            @endif

            @if($step === 4)
                @if($selectedCompetition?->type === 'tim')
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tim</label>
                            <input type="text" wire:model="team_name" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('team_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Anggota Tim</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Nama kamu sebagai ketua tim sudah otomatis terdaftar. Tambahkan minimal 1 anggota tim.</p>
                            @foreach($team_members as $index => $member)
                                <div class="flex items-center gap-2 mb-2" wire:key="member-{{ $index }}">
                                    <input type="text" wire:model="team_members.{{ $index }}" placeholder="Nama anggota {{ $index + 1 }}" class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @if(count($team_members) > 1)
                                        <button wire:click="removeTeamMember({{ $index }})" type="button" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            @error('team_members') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            @error('team_members.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            <button wire:click="addTeamMember" type="button" class="mt-2 inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Tambah Anggota
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 dark:text-gray-400">Kompetisi individu — tidak perlu data tim.</p>
                    </div>
                @endif
            @endif

            @if($step === 5)
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Review Data</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Nama</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $name }}</span>
                        </div>
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Jenis Kelamin</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $gender }}</span>
                        </div>
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Usia / Kategori</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $age }} tahun / {{ ucfirst($age_category) }}</span>
                        </div>
                        @if($phone)
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">No. HP</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $phone }}</span>
                        </div>
                        @endif
                        @if($email)
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Email</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $email }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Kompetisi</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedCompetition?->name }}</span>
                        </div>
                        @if($selectedCompetition?->type === 'tim')
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Nama Tim</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $team_name }}</span>
                        </div>
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Anggota Tim</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ implode(', ', array_filter($team_members)) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex justify-between mt-8">
                @if($step > 1 && !$submitted)
                    <button wire:click="previousStep" type="button" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Kembali</button>
                @else
                    <div></div>
                @endif

                @if($step < 5 && !$submitted)
                    <button wire:click="nextStep" type="button" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Lanjut</button>
                @elseif($step === 5 && !$submitted)
                    <button wire:click="submit" type="button" class="px-6 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">Konfirmasi & Daftar</button>
                @endif
            </div>
        </div>
    @endif
</div>
