<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('finance_pin', 6)->default('1234')->after('rejection_reason'); // PIN por defecto 1234
        });
    }
    public function down(): void { Schema::table('users', function (Blueprint $table) { $table->dropColumn('finance_pin'); }); }
};
