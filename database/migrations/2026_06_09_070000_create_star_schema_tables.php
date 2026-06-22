<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStarSchemaTables extends Migration {
    public function up() {
        // DIMENSIÓN: Doctores (Copo de Nieve: Conecta a Especialidades)
        Schema::create('dim_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_key')->unique();
            $table->string('name');
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->timestamps();
        });

        // DIMENSIÓN: Especialidades (Para el modelo Copo de Nieve)
        Schema::create('dim_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('specialty_key')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // TABLA DE HECHOS: Triage (Centro de la Estrella)
        Schema::create('fact_triages', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('dim_doctor_id')->nullable();
            $table->string('triage_level');
            $table->integer('original_age')->nullable();
            $table->integer('imputed_age'); // Edad después de fillna() y capping
            $table->boolean('was_imputed')->default(false); // ¿Se rellenó con mediana?
            $table->boolean('was_capped')->default(false); // ¿Se aplicó Min/Máx?
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('fact_triages');
        Schema::dropIfExists('dim_doctors');
        Schema::dropIfExists('dim_specialties');
    }
}
