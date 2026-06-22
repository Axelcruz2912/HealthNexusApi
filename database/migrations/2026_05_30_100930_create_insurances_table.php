<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('policy_number');
            $table->string('provider'); // IMSS, ISSSTE, GNP, AXA
            $table->enum('status', ['Vigente', 'Vencida', 'Falsa/Fraude', 'Sin Cobertura'])->default('Vigente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('insurances'); }
};
