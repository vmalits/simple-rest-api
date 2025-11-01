<?php

declare(strict_types=1);

Route::prefix('users')->group(function (): void {
    Route::get('/', App\Http\Controllers\Users\V1\IndexController::class);
    Route::get('/{user}', App\Http\Controllers\Users\V1\ShowController::class);
    Route::post('/', App\Http\Controllers\Users\V1\StoreController::class);
    Route::put('/{user}', App\Http\Controllers\Users\V1\UpdateController::class);
    Route::delete('/{user}', App\Http\Controllers\Users\V1\DestroyController::class);
});
