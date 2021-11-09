<?php

namespace Dantofema\LaravelSetup\Services\Views;

use Dantofema\LaravelSetup\Traits\CommandTrait;

class TableService
{
    use CommandTrait;

    protected string $heading = '<x-table.heading :sort: :width:>:label:</x-table.heading>';

    protected string $sort = 'sortable sortBy=":field:"';

    protected string $width = 'class="w-1/2"';

    public function getHeadings (array $columns, string $stub): string
    {
        $headings = '';
        foreach ($columns as $key => $column)
        {
            $heading = $this->heading;

            if ($key === array_key_first($columns))
            {
                $heading = str_replace(':width:', $this->width, $heading);
            }

            $heading = str_replace(':label:', $column['label'], $heading);

            if ($column['sortable'])
            {
                $sort = str_replace(':field:', $key, $this->sort);
                $heading = str_replace(':sort:', $sort, $heading);
            }
            $headings .= $heading . "\r\n";
        }

        return str_replace(
            ':table-headings:',
            $headings,
            $stub
        );
    }

    public function getCells (array $columns, string $stub): string
    {
        $cells = '';
        foreach ($columns as $key => $column)
        {
            $cell = '<x-table.cell>{{ $row->:field: }}</x-table.cell>';

            $row = explode('.', $key);

            $cell = str_replace(':field:', implode('->', $row), $cell);

            $cells .= $cell . "\r\n";
        }

        return str_replace(
            ':table-cells:',
            $cells,
            $stub
        );
    }
}