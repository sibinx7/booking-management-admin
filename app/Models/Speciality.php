<?php

namespace App\Models;

use Database\Factories\SpecialityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|Therapist[] $therapists
 */
#[Fillable([
    'name',
])]
class Speciality extends Model
{
    /** @use HasFactory<SpecialityFactory> */
    use HasFactory;

    /**
     * Get therapists that have this speciality.
     */
    public function therapists(): BelongsToMany
    {
        return $this->belongsToMany(Therapist::class, 'therapist_speciality')
            ->withPivot('extra_charge')
            ->withTimestamps();
    }
}
