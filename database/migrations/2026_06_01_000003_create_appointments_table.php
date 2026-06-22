<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('triages')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            
            $table->dateTime('scheduled_at'); // Cuándo
            $table->dateTime('estimated_end')->nullable(); // Duración estimada
            $table->enum('type', ['Consulta', 'Cirugía', 'Revisión', 'Estudio', 'Urgencia', 'Hospitalización', 'Monitoreo', 'Surtido', 'Pago']);
            $table->enum('status', ['Programada', 'Confirmada', 'En Espera', 'En Curso', 'Completada', 'Cancelada', 'No Asistió', 'Reagendada'])->default('Programada');
            $table->enum('priority', ['Normal', 'Urgente', 'Crítica'])->default('Normal');
            
            $table->string('location')->nullable(); // Consultorio, Quirófano, etc.
            $table->text('notes')->nullable();
            $table->json('reminders')->nullable(); // Alertas programadas
            $table->json('metadata')->nullable(); // Datos extra según tipo
            
            $table->timestamps();
            $table->index('scheduled_at');
            $table->index('doctor_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
