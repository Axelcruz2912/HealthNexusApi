<?php
namespace Database\Seeders;
use App\Models\Medication;
use Illuminate\Database\Seeder;
class MedicationSeeder extends Seeder {
    public function run(): void {
        Medication::create(['name' => 'Paracetamol 500mg', 'active_ingredient' => 'Paracetamol', 'stock' => 150, 'min_stock' => 20, 'type' => 'Cuadro Básico', 'price' => 25.50]);
        Medication::create(['name' => 'Omeprazol 20mg', 'active_ingredient' => 'Omeprazol', 'stock' => 85, 'min_stock' => 15, 'type' => 'Cuadro Básico', 'price' => 45.00]);
        Medication::create(['name' => 'Ketorolaco 30mg/1ml', 'active_ingredient' => 'Ketorolaco Trometamina', 'stock' => 5, 'min_stock' => 10, 'type' => 'Controlado', 'price' => 120.00]);
        Medication::create(['name' => 'Amoxicilina 500mg', 'active_ingredient' => 'Amoxicilina', 'stock' => 0, 'min_stock' => 25, 'type' => 'Cuadro Básico', 'price' => 85.00]);
        Medication::create(['name' => 'Salbutamol Inhalador', 'active_ingredient' => 'Sulfato de Salbutamol', 'stock' => 12, 'min_stock' => 5, 'type' => 'Patente', 'price' => 350.00]);
    }
}
