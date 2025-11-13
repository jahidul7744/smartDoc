<?php

namespace Database\Factories;

use App\Models\PrescriptionMedicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrescriptionMedicine>
 */
class PrescriptionMedicineFactory extends Factory
{
    protected $model = PrescriptionMedicine::class;

    public function definition(): array
    {
        return [
            'prescription_id' => function () {
                return PrescriptionFactory::new()->create()->id;
            },
            'medicine_name' => fake()->randomElement(['Paracetamol', 'Ibuprofen', 'Azithromycin']),
            'dosage' => fake()->randomElement(['500 mg', '250 mg']),
            'frequency' => fake()->randomElement(['Twice daily', 'Three times daily']),
            'duration' => fake()->randomElement(['5 days', '7 days']),
            'instructions' => fake()->randomElement(['After meals', 'Before bedtime']),
        ];
    }
}

