<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imagePath = app()->environment('testing') ? 'images/fake-image.png' : $this->faker->image('public/storage/images', 400, 300, null, false);

        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'image' => $imagePath,
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
        ];
    }
}
