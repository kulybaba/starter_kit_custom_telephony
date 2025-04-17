<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\InstallRequest;
use App\Models\User;
use App\Repositories\Crm\BoardRepository;
use App\Repositories\Crm\CustomTelephonyRepository;
use App\Repositories\CustomTelephony\WebhookRepository;
use App\Repositories\SendPulse\CredentialRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Sendpulse\MarketRestApi\Exception\ClientException;

class InstallController extends Controller
{
    /**
     * @param InstallRequest $request
     * @param CredentialRepository $credentialRepository
     * @param UserRepository $userRepository
     * @param CustomTelephonyRepository $customTelephonyRepository
     * @param WebhookRepository $webhookRepository
     * @param BoardRepository $boardRepository
     * @return RedirectResponse
     * @throws ClientException
     */
    public function __invoke(
        InstallRequest $request,
        CredentialRepository $credentialRepository,
        UserRepository $userRepository,
        CustomTelephonyRepository $customTelephonyRepository,
        WebhookRepository $webhookRepository,
        BoardRepository $boardRepository,
    ): RedirectResponse {
        $userCredentials = $credentialRepository->getByCode($request->getCode());

        $user = $userRepository->getBySpClientId($userCredentials['client_id']);
        if (empty($user)) {
            $integrationId = $customTelephonyRepository->connectCustomTelephony(
                $userCredentials['client_id'],
                $userCredentials['client_secret'],
            );
            if (empty($integrationId)) {
                throw new ClientException();
            }

            $user = User::create([
                'sp_user_id' => $userCredentials['user_id'],
                'sp_client_id' => $userCredentials['client_id'],
                'sp_client_secret' => $userCredentials['client_secret'],
                'integration_id' => $integrationId,
                'webhook_token' => $webhookRepository->getToken(),
            ]);
        }

        Auth::loginUsingId($user->id);

        return redirect()->route('app');
    }
}
