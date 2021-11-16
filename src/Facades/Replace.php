<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\ReplaceService;
use Illuminate\Support\Facades\Facade;

/**
 * @see ReplaceService
 * @method static setup()
 * @method static config(array $config, string $get)
 * @method static field(mixed $field, $requiredStub)
 * @method static end($editStub)
 */
class Replace extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'replace';
    }

}
