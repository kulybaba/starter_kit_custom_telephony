<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class CallRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'integrationId' => 'bail|required|integer',
            'phone' => 'bail|required|string',
        ];
    }

    /**
     * @return int
     */
    public function getIntegrationId(): int
    {
        return $this->integer('integrationId');
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->string('phone');
    }
}
