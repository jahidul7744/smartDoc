<?php

use App\Models\DiagnosticCenter;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\session;

it('redirects guests to login before accessing symptom input', function () {
    get(route('patient.symptoms.create'))
        ->assertRedirect(route('login'));
});

it('requires patients to select a diagnostic center before entering symptoms', function () {
    $patient = Patient::factory()->create();

    actingAs($patient->user);

    get(route('patient.symptoms.create'))
        ->assertRedirect(route('patient.diagnostic-centers.index'))
        ->assertSessionHas('status');
});

it('runs AI analysis and displays predictions with doctor recommendations', function () {
    config()->set('services.ml.base_url', 'http://ml-service.test');
    config()->set('services.ml.predict_endpoint', '/predict');
    config()->set('services.ml.timeout', 5);
    config()->set('services.ml.retry_attempts', 1);
    config()->set('services.ml.retry_delay', 1);

    $patient = Patient::factory()->create();
    $center = DiagnosticCenter::factory()->create();

    Doctor::factory()->create([
        'diagnostic_center_id' => $center->id,
        'specialization' => 'General Physician',
    ]);

    Http::fake([
        'http://ml-service.test/predict' => Http::response([
            'predicted_illness' => 'Common Cold',
            'confidence' => 0.82,
            'top_predictions' => [
                ['illness' => 'Common Cold', 'confidence' => 0.82],
                ['illness' => 'Influenza', 'confidence' => 0.1],
                ['illness' => 'Allergy', 'confidence' => 0.05],
            ],
        ], 200),
    ]);

    actingAs($patient->user);

    session([
        'patient.selected_center_id' => $center->id,
        'patient.selected_center_name' => $center->name,
    ]);

    $response = post(route('patient.symptoms.store'), [
        'symptoms' => [
            [
                'name' => 'Cough',
                'severity' => 5,
                'duration' => 3,
            ],
        ],
        'notes' => 'Feeling weak and cold.',
    ]);

    $response->assertOk()
        ->assertViewIs('patient.enter-symptoms')
        ->assertViewHas('analysisResult')
        ->assertSeeText('Common Cold')
        ->assertSeeText('General Physician');
});


