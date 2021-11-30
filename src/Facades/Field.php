<?php

namespace Dantofema\LaravelSetup\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @see FieldService
 * @method static getRules(mixed $field)
 * @method static getRelationship(mixed $field)
 * @method static getRulesToString(mixed $rules)
 * @method static config(array $config)
 * @method static searchable()
 * @method static getSearchable()
 * @method static getIndex()
 * @method static getRelationships()
 * @method static getFile()
 */
class Field extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'field';
    }
}
