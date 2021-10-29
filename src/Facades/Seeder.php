<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\SeederService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static add(array $config)
 * @method static delete(array $config)
 *
 * @see SeederService
 */
class Seeder extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'seeder';
    }
}