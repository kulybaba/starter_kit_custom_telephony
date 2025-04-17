<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class UninstallRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
        ];
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->string('client_id');
    }
}
