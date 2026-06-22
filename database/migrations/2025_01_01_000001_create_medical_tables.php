<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('prescriptions')) {
            Schema::create('prescriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_id')->constrained();
                $table->foreignId('medication_id')->constrained();
                $table->foreignId('doctor_id')->constrained('users');
                $table->string('dosis');
                $table->string('frecuencia');
                $table->string('duracion');
                $table->text('indicaciones')->nullable();
                $table->string('status')->default('Pendiente');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('medical_studies')) {
            Schema::create('medical_studies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_id')->constrained();
                $table->foreignId('doctor_id')->constrained('users');
                $table->string('tipo');
                $table->string('prioridad');
                $table->text('notas')->nullable();
                $table->string('status')->default('Solicitado');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('derivations')) {
            Schema::create('derivations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_id')->constrained();
                $table->foreignId('doctor_id')->constrained('users');
                $table->string('hospital_destino');
                $table->text('motivo');
                $table->string('status')->default('Pendiente');
                $table->timestamps();
            });
        }

        // Agregar columnas faltantes a triages
        Schema::table('triages', function (Blueprint $table) {
            if (!Schema::hasColumn('triages', 'diagnostico')) {
                $table->text('diagnostico')->nullable();
            }
            if (!Schema::hasColumn('triages', 'cie10')) {
                $table->string('cie10')->nullable();
            }
            if (!Schema::hasColumn('triages', 'tratamiento')) {
                $table->text('tratamiento')->nullable();
            }
            if (!Schema::hasColumn('triages', 'doctor_notes')) {
                $table->text('doctor_notes')->nullable();
            }
            if (!Schema::hasColumn('triages', 'assigned_doctor')) {
                $table->foreignId('assigned_doctor')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medical_studies');
        Schema::dropIfExists('derivations');
    }
};
