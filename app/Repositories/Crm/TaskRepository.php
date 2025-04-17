<?php

namespace App\Repositories\Crm;

use Sendpulse\MarketRestApi\Client;
use Sendpulse\MarketRestApi\Exception\ClientException;
use Sendpulse\MarketRestApi\Storage\FileStorage;

class TaskRepository
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
     * @param array $attributes
     * @return bool
     * @throws ClientException
     */
    public function create(string $clientId, string $clientSecret, array $attributes): bool
    {
        $response = $this->client
            ->setClientCredentials($clientId, $clientSecret, $this->fileStorage)
            ->post('crm/v1/tasks', $attributes);

        return !empty($response['data']);
    }
}
