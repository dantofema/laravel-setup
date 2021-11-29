<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\TextService;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @see TextService
 * @method static config(array $config)
 * @method static path(string $string)
 * @method static route()
 * @method static renderView()
 */
class Text extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'text';
    }

}
