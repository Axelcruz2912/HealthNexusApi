<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            if (!Schema::hasColumn('beds', 'area')) {
                $table->string('area')->default('Hospitalización');
            }
            if (!Schema::hasColumn('beds', 'patient_name')) {
                $table->string('patient_name')->nullable();
            }
            if (!Schema::hasColumn('beds', 'triage_level')) {
                $table->string('triage_level')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            if (Schema::hasColumn('beds', 'area')) { $table->dropColumn('area'); }
            if (Schema::hasColumn('beds', 'patient_name')) { $table->dropColumn('patient_name'); }
            if (Schema::hasColumn('beds', 'triage_level')) { $table->dropColumn('triage_level'); }
        });
    }
};
