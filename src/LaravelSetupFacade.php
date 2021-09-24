<?php

namespace Dantofema\LaravelSetup;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dantofema\LaravelSetup\LaravelSetup
 */
class LaravelSetupFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-setup';
    }
}
