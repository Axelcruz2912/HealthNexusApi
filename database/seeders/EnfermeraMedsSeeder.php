<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;

class EnfermeraMedsSeeder extends Seeder
{
    public function run(): void
    {
        $meds = [
            ['name' => 'Solucion Fisiologica 500ml', 'active_ingredient' => 'NaCl 0.9%', 'stock' => 80, 'min_stock' => 30, 'price' => 45.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Central', 'lot_number' => 'SF-2024-012', 'expiry_date' => '2026-03-15', 'location' => 'Estante Sueros A', 'provider_name' => 'FarmaLine'],
            ['name' => 'Suero Glucosado 5% 500ml', 'active_ingredient' => 'Dextrosa 5%', 'stock' => 60, 'min_stock' => 25, 'price' => 38.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Central', 'lot_number' => 'SG-2024-008', 'expiry_date' => '2026-05-20', 'location' => 'Estante Sueros A', 'provider_name' => 'FarmaLine'],
            ['name' => 'Oxigeno Medicinal (Tanque)', 'active_ingredient' => 'O2', 'stock' => 15, 'min_stock' => 5, 'price' => 350.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Urgencias', 'lot_number' => 'O2-2024-003', 'expiry_date' => '2027-01-01', 'location' => 'Area Gris', 'provider_name' => 'GasMedic'],
            ['name' => 'Kit Curacion Basico', 'active_ingredient' => 'Gasa, Venda, Yodo', 'stock' => 100, 'min_stock' => 30, 'price' => 85.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Urgencias', 'lot_number' => 'KC-2024-045', 'expiry_date' => '2026-08-10', 'location' => 'Carro Rojo', 'provider_name' => 'MediSupply'],
            ['name' => 'Jeringa 10ml', 'active_ingredient' => 'Plastico', 'stock' => 200, 'min_stock' => 50, 'price' => 8.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Central', 'lot_number' => 'JR-2024-089', 'expiry_date' => '2027-06-30', 'location' => 'Estante Material', 'provider_name' => 'FarmaLine'],
            ['name' => 'Alcohol 70% 500ml', 'active_ingredient' => 'Etanol', 'stock' => 50, 'min_stock' => 20, 'price' => 25.00, 'required_level' => 'Enfermera', 'enfermera_can_administer' => true, 'origin' => 'Central', 'lot_number' => 'AL-2024-034', 'expiry_date' => '2026-12-01', 'location' => 'Estante Material', 'provider_name' => 'FarmaLine'],
            ['name' => 'Adrenalina 1mg/ml', 'active_ingredient' => 'Epinefrina', 'stock' => 20, 'min_stock' => 10, 'price' => 120.00, 'required_level' => 'A', 'enfermera_can_administer' => true, 'origin' => 'Urgencias', 'lot_number' => 'ADR-2024-002', 'expiry_date' => '2025-11-30', 'location' => 'Refrigerador B1', 'provider_name' => 'FarmaControl'],
            ['name' => 'Diazepam 10mg/2ml', 'active_ingredient' => 'Diazepam', 'stock' => 15, 'min_stock' => 8, 'price' => 95.00, 'required_level' => 'A', 'enfermera_can_administer' => true, 'origin' => 'Urgencias', 'lot_number' => 'DZP-2024-001', 'expiry_date' => '2025-09-15', 'location' => 'Caja Fuerte 2', 'provider_name' => 'FarmaControl'],
        ];

        foreach ($meds as $med) {
            Medication::create($med);
        }

        $this->command->info('Medicamentos de enfermeria insertados correctamente.');
    }
}
