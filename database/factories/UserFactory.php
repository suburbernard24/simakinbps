<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => fake()->name(),
            'username'       => fake()->unique()->userName(),
            'nip'            => fake()->unique()->numerify('##################'),
            'jabatan'        => fake()->randomElement(['Pelaksana', 'Staf', 'Fungsional']),
            'golongan'       => fake()->randomElement(['II/a', 'II/b', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a']),
            'email'          => fake()->unique()->safeEmail(),
            'password'       => static::$password ??= Hash::make('password'),
            'role'           => 'pegawai',
            'is_active'      => true,
            'is_ketua_tim'   => false,
            'is_public_user' => false,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a Ketua Tim.
     */
    public function ketuaTim(): static
    {
        return $this->state(fn(array $attributes) => [
            'role'         => 'ketua_tim',
            'is_ketua_tim' => true,
        ]);
    }

    /**
     * Indicate that the user is Kepala BPS.
     */
    public function kepalaBps(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'kepala_bps',
        ]);
    }
}
