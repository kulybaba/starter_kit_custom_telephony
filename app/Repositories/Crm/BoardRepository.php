<?php

namespace App\Repositories\Crm;

use Sendpulse\MarketRestApi\Client;
use Sendpulse\MarketRestApi\Exception\ClientException;
use Sendpulse\MarketRestApi\Storage\FileStorage;

class BoardRepository
{
    /**
     * @var FileStorage $fileStorage
     */
    private FileStorage $fileStorage;

    /**
     * @param Client $client
     */
    public function __construct(
        private Client $client,
    ) {
        $this->fileStorage = new FileStorage('access_tokens/');
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return array
     * @throws ClientException
     */
    public function getAll(string $clientId, string $clientSecret): array
    {
        $response = $this->client
            ->setClientCredentials($clientId, $clientSecret, $this->fileStorage)
            ->get('crm/v1/boards', [], true);

        return $response['data']['data'] ?? [];
    }
}
