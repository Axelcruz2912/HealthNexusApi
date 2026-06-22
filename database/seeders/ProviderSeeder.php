<?php
namespace Database\Seeders;
use App\Models\Provider;
use Illuminate\Database\Seeder;
class ProviderSeeder extends Seeder {
    public function run(): void {
        Provider::create(['name' => 'Distribuidora Médica Nacional', 'rfc' => 'DMN230101AB1', 'contact_name' => 'Ing. Roberto Sánchez', 'phone' => '5512345678', 'email' => 'ventas@dmnacional.com', 'type' => 'Medicamentos']);
        Provider::create(['name' => 'Hospitales y Suministros S.A. de C.V.', 'rfc' => 'HSS920501T9A', 'contact_name' => 'Lic. María Fernández', 'phone' => '5587654321', 'email' => 'contacto@hyssuministros.mx', 'type' => 'Equipos Médicos']);
        Provider::create(['name' => 'Insumos Clínicos del Centro', 'rfc' => 'ICC180301PQ2', 'contact_name' => 'Dr. Fernando Ríos', 'phone' => '5544332211', 'email' => 'pedidos@insumosclinicos.mx', 'type' => 'Insumos']);
        Provider::create(['name' => 'Oxígeno y Gases Medicinales MX', 'rfc' => 'OGM110722K34', 'contact_name' => 'Sra. Laura Mendoza', 'phone' => '5511223344', 'email' => 'operaciones@oxigasesmx.com', 'type' => 'Servicios']);
        Provider::create(['name' => 'Nutrición Hospitalaria Premium', 'rfc' => 'NHP150810J12', 'contact_name' => 'Nut. Claudia Vega', 'phone' => '5599887766', 'email' => 'ventas@nutrihospmx.com', 'type' => 'Alimentos']);
    }
}
