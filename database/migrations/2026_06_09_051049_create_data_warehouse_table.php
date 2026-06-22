<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // Tabla DWH para datos de Triage Procesados
        Schema::create('dwh_triage_stats', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->index();
            $table->string('triage_level');
            $table->integer('total_pacientes');
            $table->float('tiempo_espera_promedio')->nullable(); // Minutos
            $table->float('desviacion_espera')->nullable();
            $table->integer('outliers_detectados')->default(0);
            $table->json('percentiles')->nullable(); // Q1, Q2(Mediana), Q3
            $table->string('dataset_partition')->default('train'); // train, test, validation
            $table->timestamps();
        });

        // Tabla para KPIs y Correlaciones
        Schema::create('dwh_kpi_correlations', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->string('metric_type'); // descriptive, predictive, correlation
            $table->float('value');
            $table->string('unit')->nullable();
            $table->timestamp('calculated_at');
        });
    }
    public function down(): void {
        Schema::dropIfExists('dwh_kpi_correlations');
        Schema::dropIfExists('dwh_triage_stats');
    }
};
