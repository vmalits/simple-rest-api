<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\V1\LoginController;
use App\Http\Controllers\Auth\V1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:limit'])->group(static function (): void {
    Route::prefix('v1')->group(function () {
        Route::post('login', [LoginController::class, '__invoke']);
        Route::post('register', [RegisterController::class, '__invoke']);
        Route::group(['middleware' => ['auth:sanctum']], base_path('routes/api/users.php'));
    });
});

