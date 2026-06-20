<?php

namespace App\Livewire\Admin;

use App\Models\Competition;
use App\Models\Registration;
use Illuminate\View\View;
use Livewire\Component;

class QueueList extends Component
{
    public ?int $competition_filter = null;

    public function render(): View
    {
        $competitions = Competition::withCount('registrations')
            ->orderBy('name')
            ->get();

        $registrations = Registration::with(['participant', 'competition', 'teamMembers'])
            ->when($this->competition_filter, fn($q) => $q->where('competition_id', $this->competition_filter))
            ->latest()
            ->get()
            ->groupBy(fn($r) => $r->competition->name);

        return view('livewire.admin.queue-list', [
            'competitions' => $competitions,
            'registrations' => $registrations,
        ]);
    }
}
