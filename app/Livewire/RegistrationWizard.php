<?php

namespace App\Livewire;

use App\Models\Competition;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\TeamMember;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RegistrationWizard extends Component
{
    public int $step = 1;

    public string $name = '';
    public string $gender = '';
    public string $birth_date = '';

    public string $phone = '';
    public string $email = '';
    public string $social_media = '';

    public ?int $competition_id = null;

    public string $team_name = '';
    public array $team_members = [''];

    public bool $submitted = false;

    public function getAgeProperty(): int
    {
        if (empty($this->birth_date)) return 0;
        return Carbon::parse($this->birth_date)->age;
    }

    public function getAgeCategoryProperty(): string
    {
        $age = $this->age;
        if ($age <= 12) return 'anak-anak';
        if ($age <= 17) return 'remaja';
        return 'dewasa';
    }

    public function getAvailableCompetitionsProperty()
    {
        if ($this->age === 0) return collect();
        return Competition::where('age_category', $this->age_category)->get();
    }

    public function getSelectedCompetitionProperty(): ?Competition
    {
        if (!$this->competition_id) return null;
        return Competition::find($this->competition_id);
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validateStep1(),
            2 => $this->validateStep2(),
            3 => $this->validateStep3(),
            4 => $this->validateStep4(),
            default => null,
        };

        $this->step = min($this->step + 1, 5);
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    public function updatedCompetitionId(): void
    {
        $this->team_name = '';
        $this->team_members = [''];
    }

    public function addTeamMember(): void
    {
        $this->team_members[] = '';
    }

    public function removeTeamMember(int $index): void
    {
        if (count($this->team_members) > 1) {
            unset($this->team_members[$index]);
            $this->team_members = array_values($this->team_members);
        }
    }

    public function submit(): void
    {
        $this->validateStep1();
        $this->validateStep2();
        $this->validateStep3();
        $this->validateStep4();

        $participant = Participant::create([
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'social_media' => $this->social_media ? json_encode(['instagram' => $this->social_media]) : null,
        ]);

        $registration = Registration::create([
            'participant_id' => $participant->id,
            'competition_id' => $this->competition_id,
            'team_name' => $this->selectedCompetition?->type === 'tim' ? $this->team_name : null,
        ]);

        if ($this->selectedCompetition?->type === 'tim') {
            foreach (array_filter($this->team_members) as $memberName) {
                TeamMember::create([
                    'registration_id' => $registration->id,
                    'name' => $memberName,
                ]);
            }
        }

        $this->submitted = true;
        $this->step = 5;
    }

    public function render()
    {
        return view('livewire.registration-wizard');
    }

    private function validateStep1(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date|before:today',
        ]);
    }

    private function validateStep2(): void
    {
        $this->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'social_media' => 'nullable|string|max:255',
        ]);

        if (empty($this->phone) && empty($this->email)) {
            throw ValidationException::withMessages([
                'phone' => __('Minimal salah satu kontak (No. HP atau Email) harus diisi.'),
                'email' => __('Minimal salah satu kontak (No. HP atau Email) harus diisi.'),
            ]);
        }
    }

    private function validateStep3(): void
    {
        $this->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $comp = $this->selectedCompetition;
        if (!$comp || $comp->age_category !== $this->age_category) {
            throw ValidationException::withMessages([
                'competition_id' => __('Kompetisi tidak tersedia untuk kategori usia Anda.'),
            ]);
        }
    }

    private function validateStep4(): void
    {
        if ($this->selectedCompetition?->type !== 'tim') return;

        $this->validate([
            'team_name' => 'required|string|max:255',
            'team_members' => 'required|array|min:1',
            'team_members.*' => 'required|string|max:255',
        ]);
    }
}
