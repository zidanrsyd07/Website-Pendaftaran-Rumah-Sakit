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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 30)->unique();
            $table->string('patient_name');
            $table->string('nik', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('service_type', ['rawat_jalan', 'rawat_inap']);
            $table->string('polyclinic');
            $table->string('doctor_name')->nullable();
            $table->enum('patient_type', ['umum', 'bpjs', 'asuransi'])->default('umum');
            $table->string('room_class')->nullable();
            $table->enum('status', ['pending', 'verified', 'in_progress', 'completed', 'cancelled', 'skipped'])->default('pending');
            $table->date('visit_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['visit_date', 'status']);
            $table->index('service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
