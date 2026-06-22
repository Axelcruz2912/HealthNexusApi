<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('medications', function (Blueprint $table) {
            $table->enum('required_level', ['A', 'B', 'C'])->default('C')->after('type'); // A=Controlado, B=Intermedio, C=Básico
            $table->enum('origin', ['Central', 'Hospitalaria', 'Quirófano', 'Urgencias'])->default('Central')->after('required_level');
        });
    }
    public function down(): void {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['required_level', 'origin']);
        });
    }
};
