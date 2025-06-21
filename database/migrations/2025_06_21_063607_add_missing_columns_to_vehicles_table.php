<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('model')->after('id');
            $table->string('plate_number')->unique()->after('model');
            $table->integer('year')->after('plate_number');
            $table->string('color')->after('year');
            $table->foreignId('driver_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['model', 'plate_number', 'year', 'color']);
            $table->foreignId('driver_id')->nullable(false)->change();
        });
    }
};
