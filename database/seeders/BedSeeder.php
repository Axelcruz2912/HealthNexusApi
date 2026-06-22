<?php
namespace Database\Seeders;
use App\Models\Bed;
use Illuminate\Database\Seeder;
class BedSeeder extends Seeder {
    public function run(): void {
        // Piso 1: Urgencias y General
        for($i=1; $i<=5; $i++) Bed::create(['floor' => 1, 'room_number' => '10'.$i, 'bed_number' => 'A', 'type' => 'General', 'status' => 'Disponible']);
        Bed::create(['floor' => 1, 'room_number' => '101', 'bed_number' => 'B', 'type' => 'General', 'status' => 'Ocupada']);
        // Piso 2: UCI
        for($i=1; $i<=3; $i++) Bed::create(['floor' => 2, 'room_number' => '20'.$i, 'bed_number' => 'A', 'type' => 'UCI', 'status' => 'Ocupada']);
        Bed::create(['floor' => 2, 'room_number' => '204', 'bed_number' => 'A', 'type' => 'UCI', 'status' => 'Limpieza']);
        // Piso 3: Pediatría
        for($i=1; $i<=4; $i++) Bed::create(['floor' => 3, 'room_number' => '30'.$i, 'bed_number' => 'A', 'type' => 'Pediatría', 'status' => 'Disponible']);
        // Piso 4: Quirófano
        for($i=1; $i<=2; $i++) Bed::create(['floor' => 4, 'room_number' => '40'.$i, 'bed_number' => 'A', 'type' => 'Quirófano', 'status' => 'Mantenimiento']);
    }
}
