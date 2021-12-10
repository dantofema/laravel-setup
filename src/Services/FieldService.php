<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class FieldService
{
    protected const KEY_SLUG = 'slug';
    protected const KEY_SEARCHABLE = 'searchable';
    protected const KEY_RELATIONSHIPS = 'relationship';
    protected const KEY_INDEX = 'index';

    public function getFile (array $config): array
    {
        foreach ($config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                return $field;
            }
        }

        return [];
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
            if (array_key_exists(self::KEY_INDEX, $field))
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

    public function getRules (array $field): array
    {
        return array_key_exists('rules', $field)
            ? $field['rules']
            : [];
    }

    public function getRelationship (array $field): array
    {
        return array_key_exists('relationship', $field)
            ? $field
            : [];
    }

    #[Pure] public function getRulesForRelationship (array $field): string
    {
        return $field['relationship']['type'] === 'belongsToMany'
            ? "'" . $field['name'] . "' => " . $this->getRulesToString($field['rules'])
            : "'editing." . $field['name'] . "' => " . $this->getRulesToString($field['rules']);
    }

    public function getRulesToString (array $rules): string
    {
        $rule = "[";

        $rule .= empty($rules['nullable']) ? "'required'," : "'nullable',";
        $rule .= empty($rules['unique']) ? '' : $rules['unique'] . ',';

        if ( ! empty($rules['extra']))
        {
            foreach ($rules['extra'] as $extra)
            {
                $rule .= "'$extra',";
            }
        }

        return $rule . "]";
    }
}
