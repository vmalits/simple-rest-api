<?php

declare(strict_types=1);

test('All app files should use strict types and avoid debug functions')
    ->expect('App')
    ->toUseStrictTypes()
    ->not->toUse(['die', 'dd', 'dump']);

test('Code should pass basic security checks')
    ->preset()
    ->security();
