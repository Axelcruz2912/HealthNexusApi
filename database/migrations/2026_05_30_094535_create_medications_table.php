<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('active_ingredient');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(10); // Umbral de alerta
            $table->enum('type', ['Cuadro Básico', 'Controlado', 'Generico', 'Patente'])->default('Cuadro Básico');
            $table->decimal('price', 8, 2)->default(0.00);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('medications'); }
};
