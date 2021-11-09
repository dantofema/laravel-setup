<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\BeforeService;
use Illuminate\Support\Facades\Facade;

/**
 * @see BeforeService
 * @method static setup()
 */
class Before extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'before';
    }
}
