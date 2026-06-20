<?php

namespace App\Livewire\Admin;

use App\Jobs\GenerateCertificate;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\View\View;
use Livewire\Component;

class CertificateManager extends Component
{
    public ?int $competition_id = null;
    public string $rank_filter = '';
    public bool $generating = false;

    public function generateAll(): void
    {
        $this->generating = true;

        $registrations = Registration::with('participant', 'competition')
            ->when($this->competition_id, fn($q) => $q->where('competition_id', $this->competition_id))
            ->get();

        foreach ($registrations as $reg) {
            $rank = match ($reg->rank) {
                1 => 'juara_1',
                2 => 'juara_2',
                3 => 'juara_3',
                default => 'participant',
            };

            dispatch(new GenerateCertificate($reg, $rank));
        }
    }

    public function generateForRegistration(int $registrationId, string $rank): void
    {
        $reg = Registration::with('participant', 'competition')->findOrFail($registrationId);
        dispatch(new GenerateCertificate($reg, $rank));
    }

    public function render(): View
    {
        $competitions = Competition::withCount('registrations')->orderBy('name')->get();

        $registrations = Registration::with(['participant', 'competition', 'teamMembers'])
            ->when($this->competition_id, fn($q) => $q->where('competition_id', $this->competition_id))
            ->when($this->rank_filter, fn($q) => $q->where('rank', match ($this->rank_filter) {
                'juara_1' => 1,
                'juara_2' => 2,
                'juara_3' => 3,
                default => null,
            }))
            ->latest()
            ->get();

        return view('livewire.admin.certificate-manager', [
            'competitions' => $competitions,
            'registrations' => $registrations,
        ]);
    }
}
