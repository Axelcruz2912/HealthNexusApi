<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->integer('floor'); // Piso 1, 2, 3, 4
            $table->string('room_number'); // Ej: 101, 202
            $table->string('bed_number'); // Ej: A, B
            $table->enum('status', ['Disponible', 'Ocupada', 'Limpieza', 'Mantenimiento'])->default('Disponible');
            $table->enum('type', ['General', 'UCI', 'Pediatría', 'Quirófano'])->default('General');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('beds'); }
};
