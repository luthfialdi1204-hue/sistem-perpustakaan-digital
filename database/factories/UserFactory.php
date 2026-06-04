<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'nama_pengguna' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'nim' => fake()->unique()->numerify('##########'),
            'nip' => 0,
            'kata_sandi' => 'password',
            'role_user' => User::ROLE_MAHASISWA,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'nim' => 0,
            'nip' => fake()->unique()->numerify('################'),
            'role_user' => User::ROLE_ADMIN,
        ]);
    }
}
