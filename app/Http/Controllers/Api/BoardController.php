<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Crm\BoardRepository;
use Illuminate\Support\Facades\Auth;
use Sendpulse\MarketRestApi\Exception\ClientException;

class BoardController extends Controller
{
    /**
     * @param BoardRepository $boardRepository
     * @return array
     * @throws ClientException
     */
    public function __invoke(BoardRepository $boardRepository): array
    {
        $user = Auth::user();
        if (empty($user)) {
            return [
                'success' => false,
            ];
        }

        return [
            'success' => true,
            'crmBoards' => $boardRepository->getAll($user->sp_client_id, $user->sp_client_secret),
        ];
    }
}
