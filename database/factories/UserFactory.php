<?php

namespace Database\Factories;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'birthday' => fake()->unique()->dateTimeBetween('-70 years', '-20 years')->format('Y-m-d'),
            'email_verified_at' => now(),
            'password' => Hash::make(static::$password ??='password'), // password
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (! $user->hasAnyRole(Roles::values())) {
                $user->assignRole(Roles::CUSTOMER->value);
            }
        });

    }
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function withEmail(string $email): static
    {
        return $this->state(fn (array $attributes) => ['email' => $email]);
    }
}
