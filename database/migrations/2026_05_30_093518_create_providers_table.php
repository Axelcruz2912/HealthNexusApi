<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rfc', 13)->unique();
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email');
            $table->enum('type', ['Medicamentos', 'Equipos Médicos', 'Insumos', 'Servicios', 'Alimentos']);
            $table->enum('status', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('providers'); }
};
