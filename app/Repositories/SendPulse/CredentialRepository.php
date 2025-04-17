<?php

namespace App\Repositories\SendPulse;

use Sendpulse\MarketRestApi\Client;
use Sendpulse\MarketRestApi\Exception\ClientException;

class CredentialRepository
{
    /**
     * @param Client $client
     */
    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @param string $code
     * @return array|null
     * @throws ClientException
     */
    public function getByCode(string $code): ?array
    {
        return $this->client->getClientCredentialsByCode(
            $code,
            config('sendpulse.app.id'),
            config('sendpulse.app.secret'),
        );
    }
}
