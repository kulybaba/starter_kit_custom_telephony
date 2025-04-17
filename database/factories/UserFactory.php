<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * @var string $model
     */
    protected $model = User::class;

    /**
     * @return array|mixed[]
     */
    public function definition(): array
    {
        return [
            'sp_user_id' => rand(100, 10000),
            'sp_client_id' => md5(rand(100, 10000)),
            'sp_client_secret' => md5(rand(100, 10000)),
            'integration_id' => rand(100, 10000),
            'webhook_token' => md5(rand(100, 10000)),
        ];
    }
}
