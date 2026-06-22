<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('triages', function (Blueprint $table) {
            $table->string('assigned_area')->default('Urgencias')->after('status'); // Médico, UCI, etc.
            $table->string('vitals_ta')->nullable()->after('assigned_area'); // Tensión Arterial
            $table->string('vitals_fc')->nullable()->after('vitals_ta'); // Frecuencia Cardíaca
            $table->string('vitals_temp')->nullable()->after('vitals_fc'); // Temperatura
            $table->string('vitals_spo2')->nullable()->after('vitals_temp'); // Oxigenación
            $table->boolean('is_derived')->default(false)->after('vitals_spo2'); // Si fue derivado
            $table->string('derivation_hospital')->nullable()->after('is_derived'); // A dónde se derivó
        });
    }
    public function down(): void {
        Schema::table('triages', function (Blueprint $table) {
            $table->dropColumn(['assigned_area', 'vitals_ta', 'vitals_fc', 'vitals_temp', 'vitals_spo2', 'is_derived', 'derivation_hospital']);
        });
    }
};
