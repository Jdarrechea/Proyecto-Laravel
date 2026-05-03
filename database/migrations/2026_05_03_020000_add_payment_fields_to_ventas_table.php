<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('metodo_pago')->nullable()->after('estado_pago');
            $table->string('numero_pago')->nullable()->after('metodo_pago');
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago', 'numero_pago']);
        });
    }
};
