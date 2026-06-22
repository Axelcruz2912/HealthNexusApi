<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('dwh_triage_stats', function (Blueprint $table) {
            $table->float('min_wait')->default(0);
            $table->float('max_wait')->default(0);
            $table->float('cv_wait')->default(0); // Coeficiente de variación
            $table->float('p10')->nullable();
            $table->float('p90')->nullable();
            $table->float('p95')->nullable();
            $table->float('p99')->nullable();
            $table->float('correlation_triage_wait')->nullable();
            $table->json('raw_document')->nullable(); // Simular documento NoSQL
        });

        // Tabla para el reporte de limpieza ETL
        Schema::create('dwh_etl_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('initial_records');
            $table->integer('nulls_dropped');
            $table->integer('duplicates_dropped');
            $table->integer('outliers_dropped');
            $table->integer('final_records');
            $table->float('quality_percentage');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dwh_etl_reports');
        Schema::table('dwh_triage_stats', function (Blueprint $table) {
            $table->dropColumn(['min_wait', 'max_wait', 'cv_wait', 'p10', 'p90', 'p95', 'p99', 'correlation_triage_wait', 'raw_document']);
        });
    }
};
