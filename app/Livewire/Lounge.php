<?php

namespace App\Livewire;

use App\Models\Participant;
use Illuminate\View\View;
use Livewire\Component;

class Lounge extends Component
{
    public function render(): View
    {
        return view('livewire.lounge', [
            'participants' => Participant::query()
                ->select('name', 'created_at')
                ->latest()
                ->limit(50)
                ->get(),
            'count' => Participant::count(),
        ]);
    }
}
