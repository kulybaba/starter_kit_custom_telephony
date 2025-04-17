<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UninstallRequest;
use App\Repositories\UserRepository;

class UninstallController extends Controller
{
    /**
     * @param UninstallRequest $request
     * @param UserRepository $userRepository
     * @return array|false[]
     */
    public function __invoke(UninstallRequest $request, UserRepository $userRepository): array
    {
        $user = $userRepository->getBySpClientId($request->getClientId());
        if (
            empty($user)
            || $user->sp_user_id != $request->integer('user_id')
            || $user->sp_client_secret != $request->string('client_secret')
        ) {
            return [
                'result' => false,
            ];
        }

        return [
            'result' => $user->delete(),
        ];
    }
}
