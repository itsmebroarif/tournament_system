<?php

namespace App\Livewire;

use App\Models\Competition;
use App\Services\SvgIllustrations;
use Illuminate\View\View;
use Livewire\Component;

class CompetitionCards extends Component
{
    public function render(): View
    {
        $competitions = Competition::query()
            ->orderBy('name')
            ->get()
            ->map(function (Competition $competition) {
                $competition->svg_content = SvgIllustrations::get($competition->svg_illustration_key);
                return $competition;
            });

        return view('livewire.competition-cards', [
            'competitions' => $competitions,
        ]);
    }
}
