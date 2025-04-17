<?php

namespace App\Http\Requests\Web;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WebhookRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $token = $this->input('token');
        if (empty($token)) {
            return false;
        }

        Auth::loginUsingId((new UserRepository())->getByToken($token)->id);

        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'call_id' => 'bail|required|integer',
            'event' => 'bail|required|string|in:call.started,call.ended',
            'type' => 'bail|required|string|in:incoming,outgoing',
            'phone' => 'bail|required|string',
            'duration' => 'bail|required|integer',
            'is_success' => 'bail|required|boolean',
            'token' => 'bail|required|string',
        ];
    }

    /**
     * @return int
     */
    public function getCallId(): int
    {
        return $this->integer('call_id');
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->string('event');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->string('type');
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->string('phone');
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->integer('duration');
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->boolean('is_success');
    }
}
