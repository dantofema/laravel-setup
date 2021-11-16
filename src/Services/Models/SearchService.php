<?php

namespace Dantofema\LaravelSetup\Services\Models;

use Dantofema\LaravelSetup\Facades\Field;

class SearchService
{

    protected const SEARCH_STUB = __DIR__ . '/../../Stubs/model/scopeSearch.stub';

    public function get (array $config, string $stub): string
    {
        $searchStub = file_get_contents(self::SEARCH_STUB);

        $query = "\$query";
        $searchableFields = Field::config($config)->getSearchable();
        foreach ($searchableFields as $key => $field)
        {
//            if ($key === array_key_first($searchableFields))
//            {
//                $query .= array_key_exists('relationships', $field)
//                    ? "\$query->whereHas('{$field['relationships']['name']}', fn(\$q) => \$q->where('{$field['relationships']['searchable']}', 'like',
//                '%' . \$search . '%'))\r\n"
//                    : "\$query->where('{$field['name']}', 'like', '%' . \$search . '%')\r\n";
//                continue;
//            }
            $query .= array_key_exists('relationships', $field)
                ? "->orWhereHas('{$field['relationships']['name']}', fn(\$q) => \$q->where('{$field['relationships']['searchable']}', 'like',
                '%' . \$search . '%'))->with('{$field['relationships']['name']}')\r\n"
                : "->orWhere('{$field['name']}', 'like', '%' . \$search . '%')\r\n";
        }

        $searchStub = str_replace(':query:', $query, $searchStub);

        return str_replace(':search:', $searchStub, $stub);
    }
}