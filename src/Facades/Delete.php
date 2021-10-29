<?php

namespace Dantofema\LaravelSetup\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static type(string $type)
 *
 * @see DeleteService
 */
class Delete extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'delete';
    }
}