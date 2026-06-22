<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('crash_carts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->enum('status', ['Completo', 'Incompleto', 'En Uso', 'Reabasteciendo'])->default('Completo');
            $table->text('contents')->nullable();
            $table->dateTime('last_checked')->nullable();
            $table->string('checked_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('crash_carts'); }
};
