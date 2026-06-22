<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de defunciones
        if (!Schema::hasTable('patient_deaths')) {
            Schema::create('patient_deaths', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_id')->constrained();
                $table->foreignId('doctor_id')->constrained('users');
                $table->foreignId('bed_id')->nullable();
                $table->dateTime('death_time');
                $table->string('cause_of_death');
                $table->string('immediate_cause')->nullable();
                $table->text('clinical_summary')->nullable();
                $table->boolean('autopsy_required')->default(false);
                $table->string('death_certificate_number')->nullable();
                $table->string('notified_family')->default('No');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Agregar columnas a triages para CRUD
        Schema::table('triages', function (Blueprint $table) {
            if (!Schema::hasColumn('triages', 'discharge_date')) {
                $table->dateTime('discharge_date')->nullable();
            }
            if (!Schema::hasColumn('triages', 'discharge_type')) {
                $table->string('discharge_type')->nullable(); // Alta, Defunción, Derivación
            }
            if (!Schema::hasColumn('triages', 'discharge_doctor_id')) {
                $table->foreignId('discharge_doctor_id')->nullable();
            }
            if (!Schema::hasColumn('triages', 'discharge_notes')) {
                $table->text('discharge_notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_deaths');
    }
};
