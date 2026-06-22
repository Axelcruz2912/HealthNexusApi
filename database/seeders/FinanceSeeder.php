<?php
namespace Database\Seeders;
use App\Models\Invoice;
use App\Models\Insurance;
use Illuminate\Database\Seeder;
class FinanceSeeder extends Seeder {
    public function run(): void {
        // Facturas
        Invoice::create(['patient_name' => 'Juan Pérez', 'concept' => 'Atención Urgencias (Triage Rojo)', 'amount' => 15000.00, 'status' => 'Seguro']);
        Invoice::create(['patient_name' => 'María López', 'concept' => 'Consulta Externa', 'amount' => 800.00, 'status' => 'Pagado']);
        Invoice::create(['patient_name' => 'Carlos Sánchez', 'concept' => 'Cirugía Apendicitis', 'amount' => 45000.00, 'status' => 'Pendiente']);
        Invoice::create(['patient_name' => 'Ana Gómez', 'concept' => 'Medicamentos Farmacia', 'amount' => 2340.50, 'status' => 'Pagado']);
        Invoice::create(['patient_name' => 'Roberto Ríos', 'concept' => 'Estancia UCI (3 noches)', 'amount' => 75000.00, 'status' => 'Vencido']);

        // Pólizas de Seguro
        Insurance::create(['patient_name' => 'Juan Pérez', 'policy_number' => 'GNP-8839201', 'provider' => 'GNP', 'status' => 'Vigente']);
        Insurance::create(['patient_name' => 'Ana Gómez', 'policy_number' => 'AXA-009283', 'provider' => 'AXA', 'status' => 'Vigente']);
        Insurance::create(['patient_name' => 'Carlos Sánchez', 'policy_number' => 'IMSS-991023', 'provider' => 'IMSS', 'status' => 'Sin Cobertura']);
        Insurance::create(['patient_name' => 'Luis Farsante', 'policy_number' => 'FRAUDE-123', 'provider' => 'Seguros Fantasía', 'status' => 'Falsa/Fraude']);
    }
}
