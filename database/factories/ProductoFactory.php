<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'marca' => $this->faker->company(),
            'categoria' => $this->faker->randomElement(['Electrónica', 'Papelería', 'Hogar', 'Moda']),
            'precio' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'imagen' => null,
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
