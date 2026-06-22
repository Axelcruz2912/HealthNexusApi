<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE medications MODIFY COLUMN required_level ENUM('A', 'B', 'C', 'Enfermera') NOT NULL DEFAULT 'C'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE medications MODIFY COLUMN required_level ENUM('A', 'B', 'C') NOT NULL DEFAULT 'C'");
    }
};
