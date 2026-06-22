<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('provider_id')->constrained();
            $table->enum('status', ['Borrador', 'Enviada', 'En Transito', 'Recibida', 'Cancelada'])->default('Borrador');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('expected_delivery')->nullable();
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_orders'); }
};
