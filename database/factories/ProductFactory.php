<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(4, true),
            'price' => $this->faker->numberBetween(10.00, 9999.99),
            'url' => $this->faker->url(),
            'description' => $this->faker->paragraphs(5, true),
        ];
    }

    public function amazon()
    {
        return $this->state(function () {
            return [
                'url' => 'https://amazon.com/'.$this->faker->numberBetween(1000, 999999),
            ];
        });
    }

    public function steam(): self
    {
        return $this->state(function () {
            return [
                'url' => 'https://steampowered.com/app/'.$this->faker->numberBetween(1000, 999999),
            ];
        });
    }

    public function example(): self
    {
        return $this->state(function () {
            return [
                'url' => 'https://example.com/product/'.$this->faker->numberBetween(1000, 999999),
            ];
        });
    }
}
