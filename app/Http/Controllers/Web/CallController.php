<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CallRequest;
use App\Repositories\CustomTelephony\CallRepository;
use App\Repositories\UserRepository;

class CallController extends Controller
{
    /**
     * @param CallRequest $request
     * @param UserRepository $userRepository
     * @param CallRepository $callRepository
     * @return false[]|true[]
     */
    public function __invoke(CallRequest $request, UserRepository $userRepository, CallRepository $callRepository): array
    {
        if (!$userRepository->existByIntegrationId($request->getIntegrationId())) {
            return [
                'result' => false,
            ];
        }

        $callRepository->call($request->getPhone());

        return [
            'result' => true,
        ];
    }
}
