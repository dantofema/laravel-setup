<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

use JetBrains\PhpStorm\Pure;

class RulesService
{
    private NewFileService $newFileService;

    #[Pure] public function __construct ()
    {
        $this->newFileService = new NewFileService();
    }

    public function get (array $config, string $stub): string
    {
        $rules = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            $rules .= match (true)
            {
                gen()->field()->isBelongsTo($field) => $this->belongsTo($field),
                gen()->field()->isBelongsToMany($field) => $this->belongsToMany($field),
                gen()->field()->isDate($field) => $this->date($field),
                default => $this->default($field)
            };

            $rules .= ',' . PHP_EOL;
        }

        $rules = "\$rules = [" . PHP_EOL . $rules . "];" . PHP_EOL;

        $stub = $this->newFileService->get($config, $stub);

        return str_replace(
            ':rules:',
            $rules,
            $stub
        );
    }

    private function belongsTo (mixed $field): string
    {
        return "'editing." . $field['name'] . "' => " . gen()->field()
                ->getRulesToString($field);
    }

    private function belongsToMany (mixed $field): string
    {
        return "'" . $field['name'] . "' => " . gen()->field()
                ->getRulesToString($field);
    }

    private function date (mixed $field): string
    {
        return "'" . $field['name'] . "' => " . gen()->field()->getRulesToString
            ($field);
    }

    private function default (array $field): string
    {
        return "'editing." . $field['name'] . "' => " . gen()->field()->getRulesToString($field);
    }

}