<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'diagnosis_id',
        'doctor_id',
        'patient_id',
        'diagnostic_center_id',
        'general_instructions',
        'follow_up_at',
        'pdf_path',
        'issued_at',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
        'issued_at' => 'datetime',
    ];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnosticCenter(): BelongsTo
    {
        return $this->belongsTo(DiagnosticCenter::class);
    }

    public function medicines(): HasMany
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
}

