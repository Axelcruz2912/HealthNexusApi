<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CrashCart;
use App\Models\Provider;

class PharmacyPlusSeeder extends Seeder {
    public function run(): void {
        // Carros de emergencia
        $carts = [
            ['name' => 'Carro Rojo - Urgencias', 'location' => 'Urgencias - Area Triage', 'status' => 'Completo', 'contents' => 'Adrenalina, Atropina, Amiodarona, Bicarbonato, Calcio, Defibrilador, Tubo Endotraqueal, Ambu', 'last_checked' => now(), 'checked_by' => 'Enf. Key'],
            ['name' => 'Carro Azul - Pediatria', 'location' => 'Pediatria - Pasillo Central', 'status' => 'Completo', 'contents' => 'Adrenalina Pediatrica, Atropina Peds, Diazepam Rectal, Mascaras Pediatricas, Ambu Pediatrico', 'last_checked' => now()->subDays(2), 'checked_by' => 'Enf. Ana'],
            ['name' => 'Carro Quirurgico', 'location' => 'Quirófano 1', 'status' => 'Incompleto', 'contents' => 'Morfina, Fentanilo, Midazolam, Succinilcolina, Kits Intubacion', 'last_checked' => now()->subDays(5), 'checked_by' => null],
            ['name' => 'Carro UCI', 'location' => 'UCI - Cabina Central', 'status' => 'Completo', 'contents' => 'Noradrenalina, Vasopresina, Dobutamina, NTP, Insulina Rapida, Jeringas Infusion', 'last_checked' => now()->subDays(1), 'checked_by' => 'Enf. Key'],
        ];
        foreach ($carts as $cart) { CrashCart::create($cart); }

        // Actualizar scores de proveedores
        Provider::where('name', 'FarmaControl')->update([
            'delivery_score' => 9, 'price_score' => 6, 'quality_score' => 10,
            'total_orders' => 45, 'late_deliveries' => 2, 'avg_delivery_days' => 1.5
        ]);
        Provider::where('name', 'GenFarma')->update([
            'delivery_score' => 7, 'price_score' => 9, 'quality_score' => 7,
            'total_orders' => 120, 'late_deliveries' => 15, 'avg_delivery_days' => 3.2
        ]);
        Provider::where('name', 'MediSupply')->update([
            'delivery_score' => 8, 'price_score' => 7, 'quality_score' => 8,
            'total_orders' => 67, 'late_deliveries' => 5, 'avg_delivery_days' => 2.1
        ]);
    }
}
