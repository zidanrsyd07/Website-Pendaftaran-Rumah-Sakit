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
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->string('queue_number', 10);
            $table->string('loket_name', 50);
            $table->enum('status', ['waiting', 'called', 'serving', 'completed', 'skipped', 'transferred'])->default('waiting');
            $table->foreignId('served_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('counter_number')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['loket_name', 'status']);
            $table->index('queue_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_tickets');
    }
};
