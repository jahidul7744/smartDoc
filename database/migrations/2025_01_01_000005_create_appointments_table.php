<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diagnostic_center_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at')->index();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending')->index();
            $table->string('chief_complaint')->nullable();
            $table->string('predicted_illness')->nullable();
            $table->json('symptoms')->nullable();
            $table->decimal('ai_confidence', 5, 2)->nullable();
            $table->foreignId('follow_up_parent_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->timestamps();

            $table->index(['doctor_id', 'scheduled_at']);
            $table->index(['patient_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

