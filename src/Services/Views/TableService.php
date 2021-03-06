<?php

namespace Dantofema\LaravelSetup\Services\Views;

class TableService
{

    protected string $heading = '<x-table.heading :sortable: :class:>:label:</x-table.heading>';

    protected string $sortable = 'sortable sortBy=":sortBy:"';

    protected string $class = 'class="w-full"';

    public function getHeadings (array $fields, string $stub): string
    {
        $headings = '';
        foreach ($fields as $key => $field)
        {
            $heading = $this->heading;

            $class = 'class="w-1/4"';
            if ($key === array_key_first($fields))
            {
                $class = $this->class;
            }
            $heading = str_replace(':class:', $class, $heading);
            $heading = str_replace(':label:', $field['label'], $heading);

            $sortable = '';
            if ( ! empty($field['sortable']))
            {
                $sortable = str_replace(':sortBy:', $field['name'], $this->sortable);
            }
            $heading = str_replace(':sortable:', $sortable, $heading);

            $headings .= $heading . "\r\n";
        }

        return str_replace(
            ':table-headings:',
            $headings,
            $stub
        );
    }

    public function getCells (array $fields, string $stub): string
    {
        $cells = '';
        foreach ($fields as $key => $field)
        {
            $cell = '<x-table.cell> :field: </x-table.cell>';

            $row = explode('.', $key);

            $cell = array_key_exists('relationship', $field)
                ? $this->relationshipCell($field['relationship'], $cell)
                : str_replace(
                    ':field:',
                    "{{ \$row->{$field['name']} }}",
                    $cell
                );

            $cells .= $cell . "\r\n";
        }

        return str_replace(
            ':table-cells:',
            $cells,
            $stub
        );
    }

    private function relationshipCell ($relationship, string $cell): string
    {
        $replace = match ($relationship['type'])
        {
            'belongsToMany' => "@foreach(\$row->" . $relationship['name'] . " as \$item) <x-table.badge color='blue'>{{ \$item->name }}</x-table.badge> @endforeach",
            'belongsTo' => "{{ optional(\$row->{$relationship['name']})->{$relationship['searchable']} }}"
        };

        return str_replace(
            ':field:',
            $replace,
            $cell
        );
    }
}
