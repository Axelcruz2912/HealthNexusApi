<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('curp', 18)->nullable()->unique()->after('role');
            $table->string('rfc', 13)->nullable()->unique()->after('curp');
            $table->string('ine_path')->nullable()->after('rfc');
            $table->string('cedula_path')->nullable()->after('ine_path');
            $table->string('certifications_path')->nullable()->after('cedula_path');
            $table->enum('validation_status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente')->after('certifications_path');
            $table->text('rejection_reason')->nullable()->after('validation_status');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['curp', 'rfc', 'ine_path', 'cedula_path', 'certifications_path', 'validation_status', 'rejection_reason']);
        });
    }
};
