<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class InstallRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:32',
        ];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->string('code');
    }
}
