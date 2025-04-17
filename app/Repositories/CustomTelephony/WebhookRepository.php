<?php

namespace App\Repositories\CustomTelephony;

use Illuminate\Support\Facades\Http;

class WebhookRepository
{
    /**
     * @return string
     */
    public function getToken(): string
    {
        $result = Http::get('https://some-custom-telephony/api/v1/webhooks/token');

        return $result['token'];
    }
}
