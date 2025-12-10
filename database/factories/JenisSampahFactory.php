<?php

namespace Database\Factories;

use App\Models\JenisSampah;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisSampahFactory extends Factory
{
    protected $model = JenisSampah::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->word,
            'kategori' => $this->faker->randomElement(array_keys(JenisSampah::KATEGORI)),
            'harga_per_kg' => $this->faker->numberBetween(1000, 10000),
            'deskripsi' => $this->faker->sentence,
            'status' => 'aktif',
        ];
    }
}
