<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('triages', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->integer('age');
            $table->enum('triage_level', ['Rojo', 'Naranja', 'Amarillo', 'Verde', 'Azul'])->default('Verde');
            $table->text('symptoms');
            $table->enum('status', ['En Espera', 'En Atención', 'Hospitalizado', 'Derivado', 'Dado de Alta'])->default('En Espera');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('triages'); }
};
