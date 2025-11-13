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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('diagnostic_center_id')->constrained()->cascadeOnDelete();
            $table->string('specialization');
            $table->string('qualifications')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->string('registration_number')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['diagnostic_center_id', 'specialization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};

