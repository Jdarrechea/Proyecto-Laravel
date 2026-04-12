<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    // 👇 NOMBRE REAL DE LA TABLA EN LA BD
    protected $table = 'promociones';

    protected $fillable = [
        'producto_id',
        'descuento',
        'fecha_inicio',
        'fecha_fin',
        'activa'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

