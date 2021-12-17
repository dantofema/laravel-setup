<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\FileSystemService;
use Dantofema\LaravelSetup\Services\Livewire\BelongsToManyService;
use Dantofema\LaravelSetup\Services\Livewire\NewFileService;
use Dantofema\LaravelSetup\Services\Livewire\SyncBelongsToManyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLivewireCommand extends Command
{

    public $signature = 'generate:livewire {path : path to the config file } {--force}';
    public $description = 'Livewire file generator';
    protected array $config;
    private NewFileService $newFileService;
    private FileSystemService $filesystem;
    private SyncBelongsToManyService $syncBelongsToMany;
    private BelongsToManyService $belongsToMany;

    public function __construct ()
    {
        parent::__construct();
        $this->newFileService = new NewFileService();
        $this->filesystem = new FileSystemService();
        $this->syncBelongsToMany = new SyncBelongsToManyService();
        $this->belongsToMany = new BelongsToManyService();
    }

    public function handle (): bool
    {
        $config = include $this->argument('path');

        if ($this->option('force'))
        {
            gen()->delete()->livewire($config);
        }

        $path = gen()->path()->livewire($config);
        $stub = gen()->stub()->livewire($config);

        File::put($path, $this->replace($config, $stub));

        return true;
    }

    private function replace (array $config, string $stub): string
    {
        $stub = $this->getNamespace($config, $stub);
        $stub = $this->sortField($config, $stub);
        $stub = $this->newFileService->get($config, $stub);
        $stub = $this->getSaveSlug($config, $stub);
        $stub = $this->getSaveDate($config, $stub);
        $stub = $this->getRules($config, $stub);
        $stub = $this->getProperties($config, $stub);
        $stub = $this->getUseCollection($config, $stub);
        $stub = $this->getQueryRelationships($config, $stub);
        $stub = $this->getLayout($config, $stub);
        $stub = $this->getDateProperties($config, $stub);
        $stub = $this->syncBelongsToMany->get($config, $stub);
        $stub = $this->belongsToMany->get($config, $stub);
        return gen()->config()->replace($config, 'livewire', $stub);
    }

    private function getNamespace (array $config, string $stub): string
    {
        return str_replace(
            ':namespace:',
            gen()->namespace()->livewire($config),
            $stub
        );
    }

    private function sortField (array $config, string $stub): string
    {
        return str_replace(
            ':sortField:',
            gen()->config()->livewireSortField($config),
            $stub
        );
    }

    private function getSaveSlug (array $config, string $stub): string
    {
        $slug = '';
        foreach ($config['fields'] as $field)
        {
            if ($field['name'] == 'slug')
            {
                $slug = "\$this->setSlug('" . $field['source'] . "');";
            }
        }
        return str_replace(
            ':saveSlug:',
            $slug,
            $stub
        );
    }

    private function getSaveDate (array $config, string $stub): string
    {
        $slug = '';
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isDate($field))
            {
                $slug = "\$this->saveDate('" . $field['name'] . "');";
            }
        }
        return str_replace(
            ':saveDate:',
            $slug,
            $stub
        );
    }

    private function getRules (array $config, string $stub): string
    {
        $rules = "\$rules = [" . PHP_EOL;
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            $rules .= match (true)
            {
                isset($field['relationship']) => gen()->field()->getRulesForRelationship($field),
                gen()->field()->isDate($field) => "'" . $field['name'] . "' => " . gen()->field()->getRulesToString($field['rules']),
                default => "'editing." . $field['name'] . "' => " . gen()->field()->getRulesToString($field['rules'])
            };

            $rules .= ',' . PHP_EOL;
        }
        $rules .= "];" . PHP_EOL;

        $fieldFile = gen()->field()->getFile($config);

        if ( ! empty($fieldFile))
        {
            $this->filesystem->execute($config);
            $fileRules = gen()->field()->getRulesToString($fieldFile['rules']);
            $rules .= <<<EOT
        if (\$this->parameterAction == 'create')
        {
            \$rules['newFile'] = $fileRules;
        }
EOT;
        }

        $rules .= PHP_EOL . " return \$rules;" . PHP_EOL;

        return str_replace(
            ':rules:',
            $rules,
            $stub
        );
    }

    private function getProperties (array $config, string $stub): string
    {
        $response = '';
        $relationshipFields = gen()->field()->getRelationships($config);
        foreach ($relationshipFields as $relationshipField)
        {
            if (gen()->field()->isBelongsToMany($relationshipField))
            {
                $response .= "public Collection $" . $relationshipField['relationship']['table'] . ";" . PHP_EOL;
                $response .= "public Collection \$"
                    . strtolower($relationshipField['relationship']['model']) . "Options;" . PHP_EOL;
                $response .= "public string \$new" . $relationshipField['relationship']['model'] . "='';" . PHP_EOL;
            }

            if (gen()->field()->isBelongsTo($relationshipField))
            {
                $response .= "public Collection $" . $relationshipField['relationship']['table'] . ";" . PHP_EOL;
            }
        }

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isTrix($field))
            {
                $response .= "private string \$disk = '" . gen()->config()->disk($config) . "';";
            }
        }
        return str_replace(':properties:', $response, $stub);
    }

    private function getUseCollection (array $config, string $stub): string
    {
        return str_replace(
            ':useCollection:',
            empty(gen()->field()->getRelationships($config))
                ? ''
                : 'use Illuminate\Support\Collection;' . PHP_EOL,
            $stub
        );
    }

    private function getQueryRelationships (array $config, string $stub): string
    {
        $response = '';
        $fields = gen()->field()->getRelationships($config);
        foreach ($fields as $field)
        {
            if (gen()->field()->isBelongsTo($field))
            {
                $response .= "\$this->" . $field['relationship']['table'] . " = " . $field['relationship']['model'] . "::all();" .
                    PHP_EOL;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $response .= "\$this->" . $field['relationship']['table'] . " = "
                    . " collect([]);" .
                    PHP_EOL;
                $response .= "\$this->"
                    . strtolower($field['relationship']['model']) . "Options = "
                    . " collect([]);" .
                    PHP_EOL;
            }
        }
        return str_replace(':queryRelationships:', $response, $stub);
    }

    private function getLayout (array $config, string $stub): string
    {
        $layout = '';

        if (gen()->config()->isBackend($config) and gen()->config()->layout($config) === 'tailwind')
        {
            $layout = "->layout('layouts.tailwind.backend.app')";
        }
        return str_replace(':layout:', $layout, $stub);
    }

    private function getDateProperties (array $config, string $stub): string
    {
        $edit = '';
        $create = '';
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isDate($field))
            {
                $edit .= "\$this->" . $field['name']
                    . " = \$this->showDate('"
                    . $field['name'] . "');";

                $create .= "\$this->" . $field['name'] . " = '';";
            }
        }
        $stub = str_replace(
            ':createDateProperties:',
            $create,
            $stub
        );
        return str_replace(
            ':editDateProperties:',
            $edit,
            $stub
        );
    }
}
