<?php

namespace App\Models;

use App\Models\Promocion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'marca',
        'categoria',
        'precio',
        'stock',
        'imagen',
        'descripcion'
    ];

    protected $casts = [
        'precio' => 'float',
        'stock' => 'integer',
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class);
    }

    public function promocionActiva()
    {
        return $this->hasOne(Promocion::class)
            ->where('activa', true)
            ->whereDate('fecha_inicio', '<=', now())
            ->whereDate('fecha_fin', '>=', now());
    }

    public function getPrecioConDescuentoAttribute(): float
    {
        if (! $this->promocionActiva) {
            return $this->precio;
        }

        return round($this->precio * (1 - $this->promocionActiva->descuento / 100), 2);
    }
}

