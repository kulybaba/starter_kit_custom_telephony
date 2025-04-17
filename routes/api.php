<?php

use App\Http\Controllers\Api\BoardController;
use Illuminate\Support\Facades\Route;

Route::get('/boards', BoardController::class);
