<?php

namespace App\Models;

use Database\Factories\TournamentMatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'competition_id',
    'round',
    'team_a_registration_id',
    'team_b_registration_id',
    'winner_registration_id',
])]
class TournamentMatch extends Model
{
    /** @use HasFactory<TournamentMatchFactory> */
    use HasFactory;

    protected $table = 'matches';

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function teamA(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team_a_registration_id');
    }

    public function teamB(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'team_b_registration_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'winner_registration_id');
    }
}
