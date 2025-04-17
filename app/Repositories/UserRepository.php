<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return User::getModel();
    }

    /**
     * @param string $spClientId
     * @return Model|null
     */
    public function getBySpClientId(string $spClientId): ?Model
    {
        return $this
            ->getModel()
            ->where('sp_client_id', $spClientId)
            ->first();
    }

    /**
     * @param string $token
     * @return Model
     */
    public function getByToken(string $token): Model
    {
        return $this
            ->getModel()
            ->where('webhook_token', $token)
            ->firstOrFail();
    }

    /**
     * @param int $integrationId
     * @return bool
     */
    public function existByIntegrationId(int $integrationId): bool
    {
        return $this
            ->getModel()
            ->where('integration_id', $integrationId)
            ->exists();
    }
}
