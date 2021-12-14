<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Services\FileSystemService;
use Dantofema\LaravelSetup\Services\Livewire\BelongsToManyService;
use Dantofema\LaravelSetup\Services\Livewire\NewFileService;
use Dantofema\LaravelSetup\Services\Livewire\SyncBelongsToManyService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;

class GenerateLivewireCommand extends Command
{
    use CommandTrait;

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

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        $types = $this->config['allInOne']
            ? ['livewireAllInOne']
            : ['livewire'];

        $this->init($types);

        foreach ($this->properties as $property)
        {
            $this->put($property['type'], $this->replace($property));
            gen()->addRoute($this->config, $property['type']);
        }

        return true;
    }

    private function replace (array $property): string
    {
        $property['stub'] = $this->getNamespace($property);
        $property['stub'] = $this->sortField($property['stub']);
        $property['stub'] = $this->newFileService->get($this->config, $property['stub']);
        $property['stub'] = $this->getSaveSlug($property['stub']);
        $property['stub'] = $this->getSaveDate($property['stub']);
        $property['stub'] = $this->getRules($property['stub']);
        $property['stub'] = $this->getProperties($property['stub']);
        $property['stub'] = $this->getUseCollection($property['stub']);
        $property['stub'] = $this->getQueryRelationships($property['stub']);
        $property['stub'] = $this->getLayout($property['stub']);
        $property['stub'] = $this->getDateProperties($property['stub']);
        $property['stub'] = $this->syncBelongsToMany->get($this->config, $property['stub']);
        return $this->belongsToMany->get($this->config, $property['stub']);
    }

    private function getNamespace (array $property): string
    {
        return str_replace(
            ':namespace:',
            gen()->getNamespace($this->config, $property['type']),
            $property['stub']
        );
    }

    private function sortField (string $stub): string
    {
        return str_replace(
            ':sortField:',
            $this->config['livewire']['properties']['sortField'],
            $stub
        );
    }

    private function getSaveSlug (string $stub): string
    {
        $slug = '';
        foreach ($this->config['fields'] as $field)
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

    private function getSaveDate (string $stub): string
    {
        $slug = '';
        foreach ($this->config['fields'] as $field)
        {
            if (isset($field['type']) and $field['type'] == 'date')
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

    private function getRules (string $stub): string
    {
        $rules = "\$rules = [" . PHP_EOL;
        foreach ($this->config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                continue;
            }

            $rules .= match (true)
            {
                isset($field['relationship']) => gen()->field()->getRulesForRelationship($field),
                $field['type'] === 'date' => "'" . $field['name'] . "' => " . gen()->field()->getRulesToString($field['rules']),
                default => "'editing." . $field['name'] . "' => " . gen()->field()->getRulesToString($field['rules'])
            };

            $rules .= ',' . PHP_EOL;
        }
        $rules .= "];" . PHP_EOL;

        $fieldFile = gen()->field()->getFile($this->config);

        if ( ! empty($fieldFile))
        {
            $this->filesystem->execute($this->config);
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

    private function getProperties (string $stub): string
    {
        $response = '';
        $relationshipFields = gen()->field()->getRelationships($this->config);
        foreach ($relationshipFields as $relationshipField)
        {
            if ($relationshipField['relationship']['type'] === 'belongsToMany')
            {
                $response .= "public Collection $" . $relationshipField['relationship']['table'] . ";" . PHP_EOL;
                $response .= "public Collection \$"
                    . strtolower($relationshipField['relationship']['model']) . "Options;" . PHP_EOL;
                $response .= "public string \$new" . $relationshipField['relationship']['model'] . "='';" . PHP_EOL;
            }

            if ($relationshipField['relationship']['type'] === 'belongsTo')
            {
                $response .= "public Collection $" . $relationshipField['relationship']['table'] . ";" . PHP_EOL;
            }
        }

        foreach ($this->config['fields'] as $field)
        {
            if (isset($field['form']['richText']))
            {
                $response .= "private string \$disk = '" . gen()->getName($this->config, 'disk') . "';";
            }
        }
        return str_replace(':properties:', $response, $stub);
    }

    private function getUseCollection (string $stub): string
    {
        return str_replace(
            ':useCollection:',
            empty(gen()->field()->getRelationships($this->config))
                ? ''
                : 'use Illuminate\Support\Collection;' . PHP_EOL,
            $stub
        );
    }

    private function getQueryRelationships (string $stub): string
    {
        $response = '';
        $fields = gen()->field()->getRelationships($this->config);
        foreach ($fields as $field)
        {
            if ($field['relationship']['type'] === 'belongsTo')
            {
                $response .= "\$this->" . $field['relationship']['table'] . " = " . $field['relationship']['model'] . "::all();" .
                    PHP_EOL;
            }

            if ($field['relationship']['type'] === 'belongsToMany')
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

    private function getLayout (string $stub): string
    {
        $layout = '';

        if ($this->config['backend'] and $this->config['view']['layout'] === 'tailwind')
        {
            $layout = "->layout('layouts.tailwind.backend.app')";
        }
        return str_replace(':layout:', $layout, $stub);
    }

    private function getDateProperties (string $stub): string
    {
        $edit = '';
        $create = '';
        foreach ($this->config['fields'] as $field)
        {
            if (isset($field['type']) and $field['type'] === 'date')
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
