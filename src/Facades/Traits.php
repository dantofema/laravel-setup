<?php

namespace Dantofema\LaravelSetup\Facades;

use Dantofema\LaravelSetup\Services\TraitService;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @see TraitService
 * @method static withSaveNewImage()
 */
class Traits extends Facade
{
    protected static function getFacadeAccessor (): string
    {
        return 'traits';
    }
}
