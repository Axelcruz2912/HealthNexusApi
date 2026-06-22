<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_id')->constrained();
                $table->foreignId('doctor_id')->constrained('users');
                $table->string('tipo');
                $table->string('prioridad');
                $table->text('descripcion')->nullable();
                $table->string('status')->default('Pendiente');
                $table->timestamps();
            });
        }
    }
    public function down() { Schema::dropIfExists('service_requests'); }
};
