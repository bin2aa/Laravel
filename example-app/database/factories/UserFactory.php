<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
            'role' => 'user',
            'remember_token' => Str::random(10),
        ];
    }

    // Tạo ra một bản ghi người dùng đã được xác minh
    public function unverified(): static
    {

        // Dùng phương thức state để tạo ra một bản ghi không được xác minh
        return $this->state(fn(array $attributes) => [
        ]);
    }
}