<?php

namespace App\Repositories\CustomTelephony;

use Illuminate\Support\Facades\Http;

class CallRepository
{
    public function call(string $phone): void
    {
        Http::post('https://some-custom-telephony/api/v1/call', [
            'to' => $phone,
        ]);
    }
}
