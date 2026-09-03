<?php

namespace App\Models;

use Database\Factories\SalaryGradeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property float $min_salary
 * @property float $max_salary
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|Employee[] $employees
 * @property-read \Illuminate\Database\Eloquent\Collection|SalaryIncrement[] $salaryIncrements
 */
#[Fillable([
    'name',
    'code',
    'min_salary',
    'max_salary',
    'description',
    'is_active',
])]
class SalaryGrade extends Model
{
    /** @use HasFactory<SalaryGradeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_salary' => 'float',
            'max_salary' => 'float',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active salary grades.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all employees assigned to this salary grade.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get all salary increment records linked to this grade.
     */
    public function salaryIncrements(): HasMany
    {
        return $this->hasMany(SalaryIncrement::class);
    }
}
