<?php

namespace App\Models;

use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'gender',
    'birth_date',
    'phone',
    'email',
    'social_media',
])]
class Participant extends Model
{
    /** @use HasFactory<ParticipantFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'social_media' => 'array',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
