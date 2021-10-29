<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\RouteService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static add(array $config)
 * @method static delete(array $config)
 *
 * @see RouteService
 */
class Route extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'route';
    }
}