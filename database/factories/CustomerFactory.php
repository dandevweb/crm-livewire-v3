<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'  => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,

            'linkedin'  => 'https://www.linkedin.com/in/' . $this->faker->userName,
            'facebook'  => 'https://www.facebook.com/' . $this->faker->userName,
            'twitter'   => 'https://www.twitter.com/' . $this->faker->userName,
            'instagram' => 'https://www.instagram.com/' . $this->faker->userName,

            'address' => $this->faker->address,
            'city'    => $this->faker->city,
            'state'   => $this->faker->state,
            'zip'     => $this->faker->postcode,
            'country' => $this->faker->country,

            'age'    => $this->faker->numberBetween(18, 65),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),

            'company'  => $this->faker->company,
            'position' => $this->faker->jobTitle,
        ];
    }
}
