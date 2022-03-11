<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'slug' => $this->faker->slug(),
            'slug' => Str::slug($this->faker->name),
            'label' => $this->faker->sentence(),
            'description' => $this->faker->text(),
            'gender'=> $this->faker->randomElement(['mixed', 'female' ,'male']),
            'mark_id'=> $this->faker->randomElement([1,2,3,4,5,]),
            'category_id' => $this->faker->randomElement([1,2,3,4]),
        ];
    }
}
