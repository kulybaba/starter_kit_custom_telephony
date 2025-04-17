<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AppController extends Controller
{
    /**
     * @return View
     */
    public function __invoke(): View
    {
        return view('app', [
            'message' => 'SendPulse Custom Telephony Starter Kit',
        ]);
    }
}
