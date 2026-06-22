<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNurseTables extends Command
{
    protected $signature = 'nurse:tables';
    protected $description = 'Create nursing tables';

    public function handle()
    {
        // Signos Vitales
        Schema::create('vital_signs', function ($t) {
            $t->id();
            $t->foreignId('triage_id')->constrained();
            $t->string('patient_name');
            $t->decimal('temperature', 4, 1)->nullable();
            $t->integer('heart_rate')->nullable();
            $t->integer('respiratory_rate')->nullable();
            $t->string('blood_pressure')->nullable();
            $t->integer('oxygen_saturation')->nullable();
            $t->decimal('weight', 5, 2)->nullable();
            $t->decimal('height', 5, 2)->nullable();
            $t->decimal('glucose', 5, 1)->nullable();
            $t->text('notes')->nullable();
            $t->foreignId('recorded_by')->constrained('users');
            $t->boolean('is_critical')->default(false);
            $t->timestamps();
        });
        $this->info('vital_signs creada');

        // Hospitalización
        Schema::create('hospitalizations', function ($t) {
            $t->id();
            $t->foreignId('triage_id')->constrained();
            $t->string('patient_name');
            $t->foreignId('bed_id')->nullable()->constrained();
            $t->enum('status', ['Ingresado', 'En Observación', 'Alta Médica', 'Transferido', 'Fallecido'])->default('Ingresado');
            $t->date('admission_date');
            $t->date('discharge_date')->nullable();
            $t->string('diagnosis')->nullable();
            $t->text('treatment')->nullable();
            $t->foreignId('assigned_doctor_id')->nullable()->constrained('users');
            $t->foreignId('assigned_nurse_id')->nullable()->constrained('users');
            $t->text('notes')->nullable();
            $t->timestamps();
        });
        $this->info('hospitalizations creada');

        // Alertas LIVE
        Schema::create('medical_alerts', function ($t) {
            $t->id();
            $t->foreignId('vital_sign_id')->nullable()->constrained();
            $t->foreignId('triage_id')->constrained();
            $t->string('patient_name');
            $t->enum('type', ['Crítico', 'Advertencia', 'Info'])->default('Advertencia');
            $t->enum('category', ['Signos Vitales', 'Medicamento', 'Hospitalización', 'Triage', 'Sistema'])->default('Signos Vitales');
            $t->text('message');
            $t->boolean('is_read')->default(false);
            $t->foreignId('triggered_by')->constrained('users');
            $t->foreignId('target_user_id')->nullable()->constrained('users');
            $t->timestamps();
        });
        $this->info('medical_alerts creada');

        // Evolución Enfermería
        Schema::create('nurse_evolutions', function ($t) {
            $t->id();
            $t->foreignId('triage_id')->constrained();
            $t->string('patient_name');
            $t->foreignId('nurse_id')->constrained('users');
            $t->text('observation');
            $t->text('intervention')->nullable();
            $t->text('response')->nullable();
            $t->enum('priority', ['Normal', 'Urgente', 'Crítica'])->default('Normal');
            $t->timestamps();
        });
        $this->info('nurse_evolutions creada');

        $this->info('');
        $this->info('TODAS las tablas de enfermería creadas!');
    }
}
