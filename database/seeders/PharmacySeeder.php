<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Medication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class PharmacySeeder extends Seeder {
    public function run(): void {
        // Crear Farmacéutico
        User::create(['name' => 'Pharma ResPOS', 'email' => 'farmacia@healthnexus.com', 'password' => Hash::make('12345678##'), 'role' => 'Farmacéutico', 'finance_pin' => '1234', 'validation_status' => 'Aprobado']);

        // Actualizar medicamentos con lógica A/B/C y Orígenes
        Medication::where('name', 'Ketorolaco 30mg/1ml')->update(['required_level' => 'A', 'origin' => 'Urgencias', 'type' => 'Controlado']);
        Medication::where('name', 'Amoxicilina 500mg')->update(['required_level' => 'B', 'origin' => 'Hospitalaria', 'type' => 'Cuadro Básico']);
        Medication::where('name', 'Paracetamol 500mg')->update(['required_level' => 'C', 'origin' => 'Central', 'type' => 'Generico']);
        Medication::where('name', 'Omeprazol 20mg')->update(['required_level' => 'C', 'origin' => 'Hospitalaria']);
        Medication::where('name', 'Salbutamol Inhalador')->update(['required_level' => 'B', 'origin' => 'Urgencias']);

        // Agregar Medicamentos de Quirófano
        Medication::create(['name' => 'Propofol 200mg/20ml', 'active_ingredient' => 'Propofol', 'stock' => 20, 'min_stock' => 5, 'type' => 'Controlado', 'price' => 450.00, 'required_level' => 'A', 'origin' => 'Quirófano']);
        Medication::create(['name' => 'Morfina 10mg/ml', 'active_ingredient' => 'Sulfato de Morfina', 'stock' => 10, 'min_stock' => 2, 'type' => 'Controlado', 'price' => 150.00, 'required_level' => 'A', 'origin' => 'Quirófano']);
    }
}
