<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medications', function (Blueprint $table) {
            if (!Schema::hasColumn('medications', 'required_level')) {
                $table->string('required_level')->nullable()->after('category');
            }
        });
    }

    public function down()
    {
        Schema::table('medications', function (Blueprint $table) {
            if (Schema::hasColumn('medications', 'required_level')) {
                $table->dropColumn('required_level');
            }
        });
    }
};
