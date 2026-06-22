<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('concept'); // Consulta, Cirugía, Farmacia
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['Pagado', 'Pendiente', 'Seguro', 'Vencido'])->default('Pendiente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('invoices'); }
};
