<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('triages'); // Relación con Urgencias
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('medication_id')->constrained('medications');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['Pendiente', 'Autorizada', 'Denegada', 'Surtida'])->default('Pendiente');
            $table->text('denial_reason')->nullable();
            $table->boolean('is_priority')->default(false); // Si viene de Triage Rojo
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('prescriptions'); }
};
