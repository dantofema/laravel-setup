<?php

namespace Dantofema\LaravelSetup\Services\Models;

class SearchService
{

    protected const SEARCH_STUB = __DIR__ . '/../../Stubs/model/scopeSearch.stub';

    public function get (array $config, string $stub): string
    {
        $searchStub = file_get_contents(self::SEARCH_STUB);

        $query = '';
        foreach ($config['model']['search'] as $key => $item)
        {
            $item = explode('.', $item);

            if ($key === array_key_first($config['model']['search']))
            {
                $query .= count($item) == 1
                    ? "\$query->where('$item[0]', 'like', '%' . \$search . '%')\r\n"
                    : "\$query->whereHas('$item[0]', fn(\$q) => \$q->where('$item[1]', 'like', '%' . \$search . '%'))\r\n";
                continue;
            }
            $query .= count($item) == 1
                ? "->orWhere('$item[0]', 'like', '%' . \$search . '%')\r\n"
                : "->orWhereHas('$item[0]', fn(\$q) => \$q->where('$item[1]', 'like', '%' . \$search . '%'))\r\n";
        }

        $searchStub = str_replace(':query:', $query, $searchStub);

        return str_replace(':search:', $searchStub, $stub);
    }
}