<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\ReplaceService;
use Illuminate\Support\Facades\Facade;

/**
 * @see ReplaceService
 *
 * @method static default()
 * @method static field(array $field)
 * @method static config(array $config)
 * @method static stub(string $string)
 */
class Replace extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'replace';
    }
}
