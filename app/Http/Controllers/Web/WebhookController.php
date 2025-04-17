<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebhookRequest;
use App\Repositories\Crm\CustomTelephonyRepository;
use App\Repositories\Crm\TaskRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Sendpulse\MarketRestApi\Exception\ClientException;

class WebhookController extends Controller
{
    /**
     * @param WebhookRequest $request
     * @param CustomTelephonyRepository $customTelephonyRepository
     * @param TaskRepository $taskRepository
     * @return true[]
     * @throws ClientException
     */
    public function __invoke(
        WebhookRequest $request,
        CustomTelephonyRepository $customTelephonyRepository,
        TaskRepository $taskRepository,
    ): array {
        $user = Auth::user();

        match ($request->getEvent()) {
            'call.started' => $customTelephonyRepository->callStart($user->sp_client_id, $user->sp_client_secret, $user->integration_id, [
                'externalCallId' => $request->getCallId(),
                'callType' => $request->getType(),
                'clientPhone' => $request->getPhone(),
            ]),
            'call.ended' => (function () use ($request, $customTelephonyRepository, $taskRepository, $user) {
                $result = $customTelephonyRepository->callEnd($user->sp_client_id, $user->sp_client_secret, $user->integration_id, [
                    'externalCallId' => $request->getCallId(),
                    'state' => $request->isSuccess() ? 'successful' : 'failed',
                    'callDuration' => $request->getDuration(),
                ]);

                if ($request->getType() === 'incoming' && !$request->isSuccess()) {
                    $taskRepository->create($user->sp_client_id, $user->sp_client_secret, [
                        'boardId' => 111826,
                        'stepId' => 337985,
                        'name' => 'Missed call',
                        'connections' => ['contactIds' => [$result['contactId']]],
                        'priority' => 1,
                        'repeat' => 0,
                        'startAt' => Carbon::now()->toIso8601ZuluString(),
                    ]);
                }
            })(),
        };

        return [
            'result' => true,
        ];
    }
}
