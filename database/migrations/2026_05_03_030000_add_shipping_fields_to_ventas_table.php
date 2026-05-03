<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('nombre_envio')->nullable()->after('numero_pago');
            $table->string('pais_envio')->nullable()->after('nombre_envio');
            $table->string('ciudad_envio')->nullable()->after('pais_envio');
            $table->string('direccion_envio')->nullable()->after('ciudad_envio');
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_envio',
                'pais_envio',
                'ciudad_envio',
                'direccion_envio',
            ]);
        });
    }
};
