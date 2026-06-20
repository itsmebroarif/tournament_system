<?php

namespace App\Livewire\Admin;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\TournamentMatch;
use Illuminate\View\View;
use Livewire\Component;

class TournamentBracket extends Component
{
    public ?int $competition_id = null;
    public array $scores = [];

    public function getTeamCompetitionsProperty()
    {
        return Competition::where('type', 'tim')->get();
    }

    public function getBracketDataProperty(): array
    {
        if (!$this->competition_id) return [];

        $matches = TournamentMatch::where('competition_id', $this->competition_id)
            ->with(['teamA.participant', 'teamB.participant', 'winner.participant'])
            ->orderBy('round')
            ->orderBy('id')
            ->get();

        $registrations = Registration::where('competition_id', $this->competition_id)
            ->with('participant')
            ->get();

        return [
            'matches' => $matches,
            'registrations' => $registrations,
            'max_round' => $matches->max('round') ?? 0,
            'has_bye' => $registrations->count() % 2 !== 0,
        ];
    }

    public function generateBracket(): void
    {
        $existing = TournamentMatch::where('competition_id', $this->competition_id)->count();
        if ($existing > 0) return;

        $registrations = Registration::where('competition_id', $this->competition_id)->get();
        if ($registrations->count() < 2) return;

        $ids = $registrations->pluck('id')->shuffle()->values();
        $matches = [];

        for ($i = 0; $i + 1 < $ids->count(); $i += 2) {
            $matches[] = [
                'competition_id' => $this->competition_id,
                'round' => 1,
                'team_a_registration_id' => $ids[$i],
                'team_b_registration_id' => $ids[$i + 1],
            ];
        }

        if ($ids->count() % 2 !== 0) {
            $matches[] = [
                'competition_id' => $this->competition_id,
                'round' => 1,
                'team_a_registration_id' => $ids->last(),
                'team_b_registration_id' => null,
                'winner_registration_id' => $ids->last(),
            ];
        }

        foreach ($matches as $match) {
            TournamentMatch::create($match);
        }
    }

    public function setWinner(int $matchId, ?int $winnerId): void
    {
        $match = TournamentMatch::findOrFail($matchId);
        $match->update(['winner_registration_id' => $winnerId]);
        $this->advanceWinner($match);
    }

    public function setScore(int $matchId, string $team, int $score): void
    {
        $this->scores[$matchId][$team] = $score;

        $match = TournamentMatch::find($matchId);
        if (!$match) return;

        if (isset($this->scores[$matchId]['a']) && isset($this->scores[$matchId]['b'])) {
            $winnerId = $this->scores[$matchId]['a'] > $this->scores[$matchId]['b']
                ? $match->team_a_registration_id
                : $match->team_b_registration_id;
            $match->update(['winner_registration_id' => $winnerId]);
            $this->advanceWinner($match);
        }
    }

    private function advanceWinner(TournamentMatch $match): void
    {
        if (!$match->winner_registration_id) return;

        $nextRound = $match->round + 1;
        $hasNextRoundMatch = TournamentMatch::where('competition_id', $match->competition_id)
            ->where('round', $nextRound)
            ->where(function ($q) {
                $q->whereNull('team_a_registration_id')
                  ->orWhereNull('team_b_registration_id');
            })
            ->first();

        if ($hasNextRoundMatch) {
            if (!$hasNextRoundMatch->team_a_registration_id) {
                $hasNextRoundMatch->update(['team_a_registration_id' => $match->winner_registration_id]);
            } else {
                $hasNextRoundMatch->update(['team_b_registration_id' => $match->winner_registration_id]);
            }
            return;
        }

        $prevRoundMatches = TournamentMatch::where('competition_id', $match->competition_id)
            ->where('round', $match->round)
            ->whereNull('winner_registration_id')
            ->count();

        if ($prevRoundMatches > 0) return;

        $nextMatch = TournamentMatch::create([
            'competition_id' => $match->competition_id,
            'round' => $nextRound,
            'team_a_registration_id' => $match->winner_registration_id,
        ]);

        $sibling = TournamentMatch::where('competition_id', $match->competition_id)
            ->where('round', $match->round)
            ->where('id', '!=', $match->id)
            ->whereNotNull('winner_registration_id')
            ->latest()
            ->first();

        if ($sibling) {
            $nextMatch->update(['team_b_registration_id' => $sibling->winner_registration_id]);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.tournament-bracket', [
            'teamCompetitions' => $this->teamCompetitions,
            'bracket' => $this->bracketData,
        ]);
    }
}
