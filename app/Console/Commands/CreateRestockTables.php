<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRestockTables extends Command
{
    protected $signature = 'pharmacy:restock-tables';
    protected $description = 'Create restock and alternatives tables';

    public function handle()
    {
        // Tabla de medicamentos alternativos
        Schema::create('medication_alternatives', function ($t) {
            $t->id();
            $t->foreignId('medication_id')->constrained();
            $t->foreignId('alternative_id')->constrained('medications');
            $t->text('notes')->nullable();
            $t->timestamps();
        });
        $this->info('medication_alternatives creada');

        // Tabla de solicitudes de reabastecimiento
        Schema::create('restock_requests', function ($t) {
            $t->id();
            $t->string('request_number')->unique();
            $t->foreignId('medication_id')->constrained();
            $t->integer('quantity_requested');
            $t->integer('quantity_approved')->default(0);
            $t->enum('priority', ['Baja', 'Media', 'Alta', 'Critica'])->default('Media');
            $t->enum('status', ['Solicitada', 'Aprobada', 'Orden Generada', 'Recibida', 'Cancelada'])->default('Solicitada');
            $t->foreignId('requested_by')->constrained('users');
            $t->foreignId('approved_by')->nullable()->constrained('users');
            $t->text('reason')->nullable();
            $t->text('notes')->nullable();
            $t->date('required_by')->nullable();
            $t->timestamps();
        });
        $this->info('restock_requests creada');

        // Insertar alternativas de medicamentos
        $alternatives = [
            ['medication_id' => 1, 'alternative_id' => 5, 'notes' => 'Morfina -> Paracetamol: Solo para dolor leve-moderado. No equivalente para dolor severo.'],
            ['medication_id' => 1, 'alternative_id' => 8, 'notes' => 'Morfina -> Ketorolaco: AINE potente. Menor riesgo respiratorio pero irritacion gastrica.'],
            ['medication_id' => 7, 'alternative_id' => 17, 'notes' => 'Fentanilo -> Adrenalina: Solo en emergencias. No es analgesico.'],
            ['medication_id' => 4, 'alternative_id' => 9, 'notes' => 'Amoxicilina -> Ceftriaxona: Cefalosporina de mayor espectro. Via IV.'],
            ['medication_id' => 4, 'alternative_id' => 6, 'notes' => 'Amoxicilina -> Omeprazol: NO es alternativa antibiotica. Solo si hay gastritis por AINEs.'],
            ['medication_id' => 3, 'alternative_id' => 10, 'notes' => 'Ketorolaco -> Ibuprofeno: Menor potencia pero disponible. Dolor moderado.'],
            ['medication_id' => 3, 'alternative_id' => 5, 'notes' => 'Ketorolaco -> Paracetamol: Para fiebre y dolor leve. Sin efecto antiinflamatorio.'],
        ];

        foreach ($alternatives as $alt) {
            DB::table('medication_alternatives')->insert(array_merge($alt, ['created_at' => now(), 'updated_at' => now()]));
        }
        $this->info('Alternativas de medicamentos insertadas');

        $this->info('');
        $this->info('TODAS las tablas creadas correctamente!');
    }
}
