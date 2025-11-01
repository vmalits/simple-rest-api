<?php

declare(strict_types=1);

test('Controllers should noting extend')
    ->expect('App\Http\Controllers')
    ->classes()
    ->toExtend('App\Http\Controllers\Controller');

test('Models should extend the base model')
    ->expect('App\Models')
    ->classes()
    ->toExtend('Illuminate\Database\Eloquent\Model');

test('Dto should extend Spatie/LaravelData')
    ->expect('App\Dtos')
    ->classes()
    ->toExtend('Spatie\LaravelData\Data');
