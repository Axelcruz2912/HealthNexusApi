<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('patient_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('triage_id')->constrained();
            $table->string('patient_name');
            $table->foreignId('medication_id')->constrained();
            $table->string('medication_name');
            $table->integer('quantity');
            $table->foreignId('dispensed_by')->constrained('users');
            $table->foreignId('prescribed_by')->constrained('users');
            $table->boolean('interaction_alert')->default(false);
            $table->text('interaction_details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('patient_medications'); }
};
