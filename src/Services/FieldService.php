<?php

namespace Dantofema\LaravelSetup\Services;

class FieldService
{

    protected const KEY_SLUG = 'slug';
    protected const KEY_SEARCHABLE = 'searchable';
    protected const KEY_RELATIONSHIPS = 'relationships';
    protected const KEY_INDEX = 'index';
    private array $config;

    public function config (array $config): FieldService
    {
        $this->config = $config;
        return $this;
    }

    public function getFile (): array
    {
        foreach ($this->config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                return $field;
            }
        }
        return [];
    }

    public function getSlug (): array
    {
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_SLUG, $field))
            {
                return $field;
            }
        }
        return [];
    }

    public function getSearchable (): array
    {
        $searchable = [];
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_SEARCHABLE, $field))
            {
                $searchable[] = $field;
            }
        }
        return $searchable;
    }

    public function getIndex (): array
    {
        $searchable = [];
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists(self::KEY_INDEX, $field))
            {
                $searchable[] = $field;
            }
        }
        return $searchable;
    }

    public function getRelationships (): array
    {
        $relationships = [];
        foreach ($this->config['fields'] as $field)
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