<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\ComponentService;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @see ComponentService
 * @method static get()
 */
class Traits extends Facade
{

    protected static function getFacadeAccessor (): string
    {
        return 'traits';
    }
}
