<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVitalsToDwhTriageStats extends Migration {
    public function up() {
        Schema::table('dwh_triage_stats', function (Blueprint $table) {
            $table->float('avg_fc', 8, 2)->nullable()->after('cv_wait'); // Frecuencia Cardíaca
            $table->float('avg_temp', 8, 2)->nullable()->after('avg_fc'); // Temperatura
            $table->float('avg_spo2', 8, 2)->nullable()->after('avg_temp'); // Saturación Oxígeno
        });
    }
    public function down() {
        Schema::table('dwh_triage_stats', function (Blueprint $table) {
            $table->dropColumn(['avg_fc', 'avg_temp', 'avg_spo2']);
        });
    }
}
