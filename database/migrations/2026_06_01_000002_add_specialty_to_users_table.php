<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('specialty_id')->nullable()->after('role')->constrained('specialties')->nullOnDelete();
            $table->json('schedule')->nullable()->after('specialty_id'); // Horarios
            $table->string('license_number')->nullable()->after('schedule'); // Cédula profesional
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['specialty_id', 'schedule', 'license_number']);
        });
    }
};
