<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'diagnostic_center_id',
        'specialization',
        'qualifications',
        'experience_years',
        'consultation_fee',
        'registration_number',
        'bio',
        'rating',
        'rating_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'consultation_fee' => 'decimal:2',
            'experience_years' => 'integer',
            'rating' => 'decimal:2',
            'rating_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diagnosticCenter(): BelongsTo
    {
        return $this->belongsTo(DiagnosticCenter::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function upcomingAppointments(): HasMany
    {
        return $this->appointments()->whereIn('status', ['pending', 'confirmed'])->orderBy('scheduled_at');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function latestPrescription(): HasOneThrough
    {
        return $this->hasOneThrough(Prescription::class, Diagnosis::class)->latestOfMany();
    }
}

