<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Services\FileSystemService;
use Dantofema\LaravelSetup\Services\Livewire\NewFileService;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;

class GenerateLivewireCommand extends Command
{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Livewire.php.stub';
    public $signature = 'generate:livewire {path : path to the config file } {--force}';
    public $description = 'Livewire file generator';
    protected array $config;
    private NewFileService $newFileService;
    private FileSystemService $filesystem;

    public function __construct()
    {
        parent::__construct();
        $this->newFileService = new NewFileService();
        $this->filesystem = new FileSystemService();
    }

    /**
     * @throws Exception
     */
    public function handle(): bool
    {
        $this->init('livewire');

        $this->getNamespace();
        $this->sortField();
        $this->stub = $this->newFileService->get($this->config, $this->stub);
        $this->getDetach();
        $this->getSaveSlug();
        $this->getRules();
        $this->getProperties();
        $this->getUseCollection();
        $this->getQueryRelationships();

        $this->put($this->stub);

        return true;
    }

    private function getNamespace(): void
    {
        $this->stub = str_replace(
            ':namespace:',
            Text::config($this->config)->namespaceFolder('livewire'),
            $this->stub
        );
    }

    private function sortField(): void
    {
        $this->stub = str_replace(
            ':sortField:',
            $this->config['livewire']['properties']['sortField'],
            $this->stub
        );
    }

    private function getDetach(): void
    {
        $response = '';

        foreach ($this->config['fields'] as $field) {
            if (array_key_exists('belongsToMany', $field)) {
                $response .= "\$this->editing->" . $field['name'] . "()->detach();\r\n";
            }
        }
        $this->stub = str_replace(':detach:', $response, $this->stub);
    }

    private function getSaveSlug(): void
    {
        $slug = '';
        foreach ($this->config['fields'] as $field) {
            if ($field['name'] == 'slug') {
                $slug = "\$this->setSlug('" . $field['source'] . "');";
            }
        }
        $this->stub = str_replace(
            ':saveSlug:',
            $slug,
            $this->stub
        );
    }

    private function getRules(): void
    {
        $rules = "\$rules = [\r\n";
        foreach ($this->config['fields'] as $field) {
            if ($field['form']['input'] !== 'file') {
                $rules .= array_key_exists('editing', $field['rules'])
                    ? "'" . $field['name'] . "' => " . Field::getRulesToString($field['rules'])
                    : "'editing." . $field['name'] . "' => " . Field::getRulesToString($field['rules']);

                $rules .= ',' . PHP_EOL;
            }
        }
        $rules .= "];\r\n";

        $fieldFile = Field::config($this->config)->getFile();
        if (! empty($fieldFile)) {
            $this->filesystem->execute($this->config);
            $fileRules = Field::getRulesToString($fieldFile['rules']);
            $rules .= <<<EOT
        if (\$this->createAction)
        {
            \$rules['newFile'] = $fileRules;
        }
EOT;
        }

        $rules .= "\r\n return \$rules;\r\n";

        $this->stub = str_replace(
            ':rules:',
            $rules,
            $this->stub
        );
    }

    private function getProperties(): void
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
        foreach ($fields as $field) {
            $response .= "public Collection $" . $field['relationship']['table'] . ";" . PHP_EOL;
        }
        $this->stub = str_replace(':properties:', $response, $this->stub);
    }

    private function getUseCollection(): void
    {
        $this->stub = str_replace(
            ':useCollection:',
            empty(Field::config($this->config)->getRelationships())
                ? ''
                : 'use Illuminate\Support\Collection;' . PHP_EOL,
            $this->stub
        );
    }

    private function getQueryRelationships(): void
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
        foreach ($fields as $field) {
            $response .= "\$this->" . $field['relationship']['table'] . " = " . $field['relationship']['model'] . "::all();" .
                PHP_EOL;
        }
        $this->stub = str_replace(':queryRelationships:', $response, $this->stub);
    }
}
