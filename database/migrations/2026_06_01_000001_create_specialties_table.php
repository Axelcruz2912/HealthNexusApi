<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->default('fa-stethoscope');
            $table->string('color', 7)->default('#F97316');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable(); // Permisos específicos del área
            $table->json('restricted_medications')->nullable(); // Medicamentos que solo esta especialidad puede usar
            $table->json('ai_config')->nullable(); // Configuración de IA para esta especialidad
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insertar especialidades base
        DB::table('specialties')->insert([
            ['name' => 'Cardiología', 'icon' => 'fa-heartbeat', 'color' => '#DC2626', 'description' => 'Corazón y sistema cardiovascular', 'permissions' => json_encode(['ecg', 'presion_arterial']), 'restricted_medications' => json_encode(['Amiodarona', 'Adenosina']), 'ai_config' => json_encode(['risk_infarto' => true, 'presion_alta' => true])],
            ['name' => 'Neurología', 'icon' => 'fa-brain', 'color' => '#7C3AED', 'description' => 'Sistema nervioso y cerebro', 'permissions' => json_encode(['glasgow', 'eval_neuro']), 'restricted_medications' => json_encode(['Levetiracetam', 'Manitol']), 'ai_config' => json_encode(['convulsiones' => true, 'acv' => true])],
            ['name' => 'Pediatría', 'icon' => 'fa-baby', 'color' => '#F59E0B', 'description' => 'Niños y adolescentes', 'permissions' => json_encode(['peso_dosificacion', 'vacunas']), 'restricted_medications' => null, 'ai_config' => json_encode(['deshidratacion' => true])],
            ['name' => 'Ginecología', 'icon' => 'fa-venus', 'color' => '#EC4899', 'description' => 'Salud de la mujer', 'permissions' => json_encode(['embarazo', 'ultrasonido']), 'restricted_medications' => null, 'ai_config' => json_encode(['preeclampsia' => true])],
            ['name' => 'Oncología', 'icon' => 'fa-ribbon', 'color' => '#8B5CF6', 'description' => 'Cáncer y tumores', 'permissions' => json_encode(['quimioterapia', 'biopsias']), 'restricted_medications' => json_encode(['Cisplatino', 'Doxorrubicina']), 'ai_config' => null],
            ['name' => 'Psiquiatría', 'icon' => 'fa-comments', 'color' => '#06B6D4', 'description' => 'Salud mental', 'permissions' => json_encode(['eval_psicologica']), 'restricted_medications' => json_encode(['Clonazepam', 'Haloperidol']), 'ai_config' => null],
            ['name' => 'Urgenciología', 'icon' => 'fa-ambulance', 'color' => '#EF4444', 'description' => 'Urgencias y emergencias', 'permissions' => json_encode(['triage_avanzado', 'defibrilador']), 'restricted_medications' => null, 'ai_config' => json_encode(['sepsis' => true, 'shock' => true])],
            ['name' => 'Medicina Interna', 'icon' => 'fa-user-md', 'color' => '#F97316', 'description' => 'Medicina general hospitalaria', 'permissions' => null, 'restricted_medications' => null, 'ai_config' => null],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};
