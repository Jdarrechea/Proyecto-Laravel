<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'estado_pago',
        'metodo_pago',
        'numero_pago',
        'nombre_envio',
        'pais_envio',
        'ciudad_envio',
        'direccion_envio',
        'referencia_pago',
    ];

    protected $casts = [
        'total' => 'float',
    ];

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
