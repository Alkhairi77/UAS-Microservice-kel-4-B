<?php

namespace Database\Factories;

use App\Models\JenisLimbah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisLimbah>
 */
class JenisLimbahFactory extends Factory
{
    protected $model = JenisLimbah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement(['B3', 'Non-B3', 'Organik', 'Anorganik']);
        $prefix = $kategori === 'B3' ? 'B3' : 'NB3';
        
        return [
            'kode_limbah' => $prefix . '-' . strtoupper($this->faker->unique()->lexify('???')),
            'nama_limbah' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(10),
            'kategori' => $kategori,
            'satuan_default' => $this->faker->randomElement(['kg', 'ton', 'liter', 'm3']),
            'status' => $this->faker->randomElement(['aktif', 'non-aktif']),
        ];
    }

    /**
     * Indicate that the jenis limbah is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the jenis limbah is B3 category.
     */
    public function b3(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'B3',
            'kode_limbah' => 'B3-' . strtoupper($this->faker->unique()->lexify('???')),
        ]);
    }

    /**
     * Indicate that the jenis limbah is Non-B3 category.
     */
    public function nonB3(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'Non-B3',
            'kode_limbah' => 'NB3-' . strtoupper($this->faker->unique()->lexify('???')),
        ]);
    }
}
