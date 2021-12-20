<?php

namespace Dantofema\LaravelSetup\Services;

class FieldService
{
    protected const KEY_SLUG = 'slug';
    protected const KEY_SEARCHABLE = 'searchable';
    protected const KEY_RELATIONSHIPS = 'relationship';
    protected const KEY_INDEX = 'index';

    public function getFiles (array $config): array
    {
        $fields = [];
        foreach ($config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    public function replace (array $field, array $config, string $stub): string
    {
        return (new ReplaceService())->fromField($field, $config, $stub);
    }

    public function isBelongsToMany (array $field): bool
    {
        return isset($field['relationship']) and
            $field['relationship']['type'] === 'belongsToMany';
    }

    public function isBelongsTo (array $field): bool
    {
        return isset($field['relationship']) and
            $field['relationship']['type'] === 'belongsTo';
    }

    public function isDate (array $field): bool
    {
        return isset($field['type'])
            and ($field['type'] === 'date' or strtolower($field['type']) === 'datetime');
    }

    public function isDateTime (array $field): bool
    {
        return isset($field['type'])
            and strtolower($field['type']) === 'datetime';
    }

    public function isBool (array $field): bool
    {
        return isset ($field['form']['input']) and
            ($field['form']['input'] === 'boolean' or
                $field['form']['input'] === 'bool');
    }

    public function getSlug (array $config): array
    {
        foreach ($config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_SLUG, $field))
            {
                return $field;
            }
        }

        return [];
    }

    public function getSearchable (array $config): array
    {
        $searchable = [];
        foreach ($config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_SEARCHABLE, $field))
            {
                $searchable[] = $field;
            }
        }

        return $searchable;
    }

    public function getIndex (array $config): array
    {
        $searchable = [];
        foreach ($config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_INDEX, $field) and $field['index'] === true)
            {
                $searchable[] = $field;
            }
        }

        return $searchable;
    }

    public function getRelationships (array $config): array
    {
        $relationships = [];
        foreach ($config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_RELATIONSHIPS, $field))
            {
                $relationships[] = $field;
            }
        }

        return $relationships;
    }

    public function getRelationship (array $field): array
    {
        return array_key_exists('relationship', $field)
            ? $field
            : [];
    }

    public function isFile (array $field): bool
    {
        return isset($field['form']['input']) and
            $field['form']['input'] === 'file';
    }

    public function getRules (array $field): array
    {
        return array_key_exists('rules', $field)
            ? $field['rules']
            : [];
    }

    public function isTrix (array $field): bool
    {
        return isset($field['form']['richText']);
    }

    public function isNullable (array $field): bool
    {
        return isset($field['rules']['nullable']);
    }

    public function getRulesToString (mixed $field): string
    {
        $rule = "[";

        $rule .= gen()->field()->isRequired($field) ? "'required'," : "'nullable',";
        $rule .= gen()->field()->isUnique($field) ? $field['rules']['unique'] . ',' : '';

        if ( ! empty($field['rules']['extra']))
        {
            foreach ($field['rules']['extra'] as $extra)
            {
                $rule .= "'$extra',";
            }
        }

        return $rule . "]";
    }

    public function isRequired (array $field): bool
    {
        return ! isset($field['rules']['nullable']) and $this->hasInput($field);
    }

    public function hasInput (array $field): bool
    {
        return boolval($field['form']['input']);
    }

    public function isUnique (array $field): bool
    {
        return isset($field['rules']['unique']);
    }
}
