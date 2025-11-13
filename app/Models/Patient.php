<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medical_history',
        'blood_group',
        'allergies',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'profile_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasCompletedProfile(): bool
    {
        $requiredAttributes = [
            'medical_history',
            'blood_group',
            'allergies',
            'emergency_contact_name',
            'emergency_contact_phone',
        ];

        foreach ($requiredAttributes as $attribute) {
            if (blank($this->{$attribute})) {
                return false;
            }
        }

        return true;
    }

    public function profileCompletionProgress(): int
    {
        $requiredAttributes = [
            'medical_history',
            'blood_group',
            'allergies',
            'emergency_contact_name',
            'emergency_contact_phone',
        ];

        $completed = collect($requiredAttributes)->filter(
            fn (string $attribute) => filled($this->{$attribute})
        )->count();

        return (int) round(($completed / count($requiredAttributes)) * 100);
    }
}

