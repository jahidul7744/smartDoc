<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'patient_id',
        'doctor_id',
        'diagnostic_center_id',
        'scheduled_at',
        'status',
        'chief_complaint',
        'predicted_illness',
        'symptoms',
        'ai_confidence',
        'follow_up_parent_id',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'symptoms' => 'array',
        'ai_confidence' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Appointment $appointment): void {
            if (blank($appointment->uuid)) {
                $appointment->uuid = (string) Str::uuid();
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function diagnosticCenter(): BelongsTo
    {
        return $this->belongsTo(DiagnosticCenter::class);
    }

    public function diagnosis(): HasOne
    {
        return $this->hasOne(Diagnosis::class);
    }

    public function prescription(): HasOneThrough
    {
        return $this->hasOneThrough(Prescription::class, Diagnosis::class);
    }

    public function followUpParent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'follow_up_parent_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(self::class, 'follow_up_parent_id');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed'])->where('scheduled_at', '>=', now());
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('scheduled_at', '<', now());
    }
}

