<?php

namespace App\Repositories\Crm;

use Sendpulse\MarketRestApi\Client;
use Sendpulse\MarketRestApi\Exception\ClientException;
use Sendpulse\MarketRestApi\Storage\FileStorage;

class CustomTelephonyRepository
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
     * @return int|null
     * @throws ClientException
     */
    public function connectCustomTelephony(string $clientId, string $clientSecret): ?int
    {
        $response = $this->client
            ->setClientCredentials($clientId, $clientSecret, $this->fileStorage)
            ->post('crm/v1/custom-telephony/connect', [
                'appId' => config('sendpulse.app.id'),
            ]);

        return !empty($response['data']['integrationId']) ? $response['data']['integrationId'] : null;
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param int $integrationId
     * @param array $attributes
     * @return array|null
     * @throws ClientException
     */
    public function callStart(string $clientId, string $clientSecret, int $integrationId, array $attributes = []): ?array
    {
        $response = $this->client
            ->setClientCredentials($clientId, $clientSecret, $this->fileStorage)
            ->post("crm/v1/custom-telephony/{$integrationId}/handle-started-call", $attributes);

        return !empty($response['data']) ? $response['data'] : null;
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param int $integrationId
     * @param array $attributes
     * @return array|null
     * @throws ClientException
     */
    public function callEnd(string $clientId, string $clientSecret, int $integrationId, array $attributes = []): ?array
    {
        $response = $this->client
            ->setClientCredentials($clientId, $clientSecret, $this->fileStorage)
            ->post("crm/v1/custom-telephony/{$integrationId}/handle-ended-call", $attributes);

        return !empty($response['data']) ? $response['data'] : null;
    }
}
