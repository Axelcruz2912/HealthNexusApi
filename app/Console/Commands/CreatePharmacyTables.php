<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyTables extends Command
{
    protected $signature = 'pharmacy:tables';
    protected $description = 'Create pharmacy tables';

    public function handle()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('patient_medications');
        Schema::dropIfExists('crash_carts');

        Schema::create('purchase_orders', function ($t) {
            $t->id();
            $t->string('po_number')->unique();
            $t->foreignId('provider_id')->constrained();
            $t->enum('status', ['Borrador', 'Enviada', 'En Transito', 'Recibida', 'Cancelada'])->default('Borrador');
            $t->decimal('total_amount', 12, 2)->default(0);
            $t->date('expected_delivery')->nullable();
            $t->date('received_date')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
        $this->info('purchase_orders creada');

        Schema::create('order_items', function ($t) {
            $t->id();
            $t->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $t->foreignId('medication_id')->constrained();
            $t->integer('quantity');
            $t->decimal('unit_price', 10, 2);
            $t->decimal('subtotal', 12, 2);
            $t->timestamps();
        });
        $this->info('order_items creada');

        Schema::create('patient_medications', function ($t) {
            $t->id();
            $t->foreignId('triage_id')->constrained();
            $t->string('patient_name');
            $t->foreignId('medication_id')->constrained();
            $t->string('medication_name');
            $t->integer('quantity');
            $t->foreignId('dispensed_by')->constrained('users');
            $t->foreignId('prescribed_by')->constrained('users');
            $t->boolean('interaction_alert')->default(false);
            $t->text('interaction_details')->nullable();
            $t->timestamps();
        });
        $this->info('patient_medications creada');

        Schema::create('crash_carts', function ($t) {
            $t->id();
            $t->string('name');
            $t->string('location');
            $t->enum('status', ['Completo', 'Incompleto', 'En Uso', 'Reabasteciendo'])->default('Completo');
            $t->text('contents')->nullable();
            $t->dateTime('last_checked')->nullable();
            $t->string('checked_by')->nullable();
            $t->timestamps();
        });
        $this->info('crash_carts creada');

        if (!Schema::hasColumn('providers', 'delivery_score')) {
            Schema::table('providers', function ($t) {
                $t->integer('delivery_score')->default(5);
                $t->integer('price_score')->default(5);
                $t->integer('quality_score')->default(5);
                $t->integer('total_orders')->default(0);
                $t->integer('late_deliveries')->default(0);
                $t->decimal('avg_delivery_days', 4, 1)->default(0);
            });
            $this->info('Provider scoring columns agregadas');
        }

        DB::table('crash_carts')->insert([
            ['name' => 'Carro Rojo - Urgencias', 'location' => 'Urgencias - Area Triage', 'status' => 'Completo', 'contents' => 'Adrenalina, Atropina, Amiodarona, Bicarbonato, Calcio, Defibrilador, Tubo Endotraqueal, Ambu', 'last_checked' => now(), 'checked_by' => 'Enf. Key', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carro Azul - Pediatria', 'location' => 'Pediatria - Pasillo Central', 'status' => 'Completo', 'contents' => 'Adrenalina Pediatrica, Atropina Peds, Diazepam Rectal, Mascaras Pediatricas, Ambu Pediatrico', 'last_checked' => now()->subDays(2), 'checked_by' => 'Enf. Ana', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carro Quirurgico', 'location' => 'Quirofano 1', 'status' => 'Incompleto', 'contents' => 'Morfina, Fentanilo, Midazolam, Succinilcolina, Kits Intubacion', 'last_checked' => now()->subDays(5), 'checked_by' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carro UCI', 'location' => 'UCI - Cabina Central', 'status' => 'Completo', 'contents' => 'Noradrenalina, Vasopresina, Dobutamina, NTP, Insulina Rapida, Jeringas Infusion', 'last_checked' => now()->subDay(), 'checked_by' => 'Enf. Key', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->info('4 crash carts insertados');

        DB::table('providers')->where('name', 'FarmaControl')->update(['delivery_score' => 9, 'price_score' => 6, 'quality_score' => 10, 'total_orders' => 45, 'late_deliveries' => 2, 'avg_delivery_days' => 1.5]);
        DB::table('providers')->where('name', 'GenFarma')->update(['delivery_score' => 7, 'price_score' => 9, 'quality_score' => 7, 'total_orders' => 120, 'late_deliveries' => 15, 'avg_delivery_days' => 3.2]);
        DB::table('providers')->where('name', 'MediSupply')->update(['delivery_score' => 8, 'price_score' => 7, 'quality_score' => 8, 'total_orders' => 67, 'late_deliveries' => 5, 'avg_delivery_days' => 2.1]);
        $this->info('Provider scores actualizados');

        $this->info('');
        $this->info('VERIFICACION:');
        $this->info('purchase_orders: ' . DB::table('purchase_orders')->count() . ' registros');
        $this->info('order_items: ' . DB::table('order_items')->count() . ' registros');
        $this->info('patient_medications: ' . DB::table('patient_medications')->count() . ' registros');
        $this->info('crash_carts: ' . DB::table('crash_carts')->count() . ' registros');

        $this->info('');
        $this->info('TODAS las tablas creadas correctamente!');
    }
}
