<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Web\CallController;
use App\Http\Controllers\Web\InstallController;
use App\Http\Controllers\Web\UninstallController;
use App\Http\Controllers\Web\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', AppController::class)->name('app');
Route::post('/install', InstallController::class)->name('login');
Route::post('/uninstall', UninstallController::class);

Route::prefix('/custom-telephony')->group(static function () {
    Route::post('/call', CallController::class);
    Route::post('/webhooks', WebhookController::class);
});
