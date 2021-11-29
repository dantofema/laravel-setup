<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\GenerateService;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static delete(mixed $config, mixed $type)
 * @method static addRoute(mixed $config)
 * @method static setup()
 * @method static removeRoute(array $config)
 * @method static addSeeder(array $config)
 *
 *  * @see GenerateService
 */
class Generate extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'generate';
    }
}