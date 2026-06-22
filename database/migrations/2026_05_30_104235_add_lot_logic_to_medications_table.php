<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->string('lot_number')->nullable()->after('price');
            $table->date('expiry_date')->nullable()->after('lot_number');
            $table->string('location')->nullable()->after('expiry_date');
            $table->string('provider_name')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['lot_number', 'expiry_date', 'location', 'provider_name']);
        });
    }
};
