<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
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
        if ($this->config['allInOne'])
        {
            $this->types = ['livewire'];
        } else
        {
            $this->types = ['livewireCollection', 'livewireModel'];
        }

        $this->init();

        foreach ($this->stubs as $stub)
        {
            $this->put($this->replace($stub));
        }

        return true;
    }

    private function replace (string $stub): string
    {
        $stub = $this->getNamespace($stub);
        $stub = $this->sortField($stub);
        $stub = $this->newFileService->get($this->config, $stub);
        $stub = $this->getSaveSlug($stub);
        $stub = $this->getRules($stub);
        $stub = $this->getProperties($stub);
        $stub = $this->getUseCollection($stub);
        $stub = $this->getQueryRelationships($stub);
        $stub = $this->getLayout($stub);
        $stub = $this->syncBelongsToMany->get($this->config, $stub);
        return $this->belongsToMany->get($this->config, $stub);
    }

    private function getNamespace (string $stub): string
    {
        return str_replace(
            ':namespace:',
            Text::config($this->config)->namespaceFolder('livewire'),
            $stub
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

    private function getRules (string $stub): string
    {
        $rules = "\$rules = [\r\n";
        foreach ($this->config['fields'] as $field)
        {
            if ($field['form']['input'] === 'file')
            {
                continue;
            }
            $rules .= isset($field['relationship'])
                ? $this->getRulesForRelationship($field)
                : "'editing." . $field['name'] . "' => " . Field::getRulesToString($field['rules']);

            $rules .= ',' . PHP_EOL;
        }
        $rules .= "];\r\n";

        $fieldFile = Field::config($this->config)->getFile();

        if ( ! empty($fieldFile))
        {
            $this->filesystem->execute($this->config);
            $fileRules = Field::getRulesToString($fieldFile['rules']);
            $rules .= <<<EOT
        if (\$this->createAction)
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

    private function getRulesForRelationship (array $field): string
    {
        return $field['relationship']['type'] === 'belongsToMany'
            ? "'" . $field['name'] . "' => " . Field::getRulesToString($field['rules'])
            : "'editing." . $field['name'] . "' => " . Field::getRulesToString($field['rules']);
    }

    private function getProperties (string $stub): string
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
        foreach ($fields as $field)
        {
            if ($field['relationship']['type'] === 'belongsToMany')
            {
                $response .= "public Collection $" . $field['relationship']['table'] . ";" . PHP_EOL;
                $response .= "public Collection \$"
                    . strtolower($field['relationship']['model']) . "Options;" . PHP_EOL;
                $response .= "public string \$new" . $field['relationship']['model'] . "='';" . PHP_EOL;
            }

            if ($field['relationship']['type'] === 'belongsTo')
            {
                $response .= "public Collection $" . $field['relationship']['table'] . ";" . PHP_EOL;
            }
        }
        return str_replace(':properties:', $response, $stub);
    }

    private function getUseCollection (string $stub): string
    {
        return str_replace(
            ':useCollection:',
            empty(Field::config($this->config)->getRelationships())
                ? ''
                : 'use Illuminate\Support\Collection;' . PHP_EOL,
            $stub
        );
    }

    private function getQueryRelationships (string $stub): string
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
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
}
