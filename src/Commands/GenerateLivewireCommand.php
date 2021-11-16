<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLivewireCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Livewire.php.stub';
    public $signature = 'generate:livewire {path : path to the config file } {--force}';
    public $description = 'Livewire file generator';
    protected array $config;

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        $this->init('livewire');
        return $this->create();
    }

    private function create (): bool
    {
        return File::put(
            Text::config($this->config)->path('livewire'),
            $this->replace());
    }

    private function replace (): string
    {
        $this->stub = $this->getNamespace();
        $this->stub = $this->getUseModels();
        $this->stub = str_replace(
            ':className:',
            ucfirst($this->config['table']['name']),
            $this->stub
        );
        $this->stub = $this->sortField();
        $this->stub = $this->getNewFile();
        $this->stub = $this->getModelName();
        $this->stub = $this->getDetach();
        $this->stub = $this->getEditNewFile();
        $this->stub = $this->getCreateNewFile();
        $this->stub = $this->getSaveNewFile();
        $this->stub = $this->getSaveSlug();
        $this->stub = $this->getRules();
        $this->stub = $this->getView();
        $this->stub = $this->getProperties();
        $this->stub = $this->getUseCollection();
        $this->stub = $this->getQueryRelationships();

        return $this->stub;
    }

    private function getNamespace (): string
    {
        $namespace = explode('\\', Text::config($this->config)->namespace('livewire'));
        array_pop($namespace);

        return str_replace(
            ':namespace:',
            implode("\\", $namespace),
            $this->stub
        );
    }

    private function getUseModels (): string
    {
        $response = 'use ' . Text::config($this->config)->namespace('model') . "\r\n";
        foreach ($this->config['fields'] as $field)
        {
            $response .= array_key_exists('relationships', $field)
                ? "use App\Models\\" . $field['relationships']['model'] . ";\r\n"
                : '';
        }

        return str_replace(':useModels:', $response, $this->stub);
    }

    private function sortField (): string
    {
        return str_replace(
            ':sortField:',
            $this->config['livewire']['properties']['sortField'],
            $this->stub
        );
    }

    private function getNewFile (): string
    {
        return str_replace(':newFile:',
            $this->getFieldFile() ? 'public $newFile;' : '',
            $this->stub
        );
    }

    private function getFieldFile ()
    {
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists('disk', $field))
            {
                return $field;
            }
        }
        return false;
    }

    private function getModelName (): string
    {
        return str_replace(':modelName:',
            $this->config['model']['name'],
            $this->stub);
    }

    private function getDetach (): string
    {
        $response = '';

        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists('belongsToMany', $field))
            {
                $response .= "\$this->editing->" . $field['name'] . "()->detach();\r\n";
            }
        }
        return str_replace(':detach:', $response, $this->stub);
    }

    private function getEditNewFile (): string
    {
        return str_replace(':editNewFile:',
            $this->getFieldFile() ? '$this->newFile = "";' : '',
            $this->stub
        );
    }

    private function getCreateNewFile (): string
    {
        return str_replace(':createNewFile:',
            $this->getFieldFile() ? '$this->newFile = null;' : '',
            $this->stub
        );
    }

    private function getSaveNewFile (): string
    {
        $replace = '';
        if ($field = $this->getFieldFile())
        {
            $name = $field['name'];
            $disk = $field['disk'];

            $replace = "\$this->setNewFile('$name', '$disk');";
        }

        return str_replace(':saveNewFile:',
            $replace,
            $this->stub
        );
    }

    private function getSaveSlug (): string
    {
        $slug = '';
        foreach ($this->config['fields'] as $field)
        {
            if ($field['name'] == 'slug')
            {
                $slug = "\$this->setSlug('" . $field['source'] . "');";
            }
        }
        return str_replace(':saveSlug:',
            $slug,
            $this->stub
        );
    }

    private function getRules (): string
    {
        $rules = "\$rules = [\r\n";
        foreach ($this->config['fields'] as $field)
        {
            if ( ! array_key_exists('disk', $field))
            {
                $rules .= array_key_exists('editing', $field['rules'])
                    ? "'" . $field['name'] . "' => " . Field::getRulesToString($field['rules'])
                    : "'editing." . $field['name'] . "' => " . Field::getRulesToString($field['rules']);

                $rules .= ',' . PHP_EOL;
            }
        }
        $rules .= "];\r\n";

        $fieldFile = Field::config($this->config)->getFile();
        if ( ! empty($fieldFile))
        {
            $fileRules = Field::getRulesToString($fieldFile['rules']);
            $rules .= <<<EOT
        if (\$this->createAction)
        {
            \$rules['newFile'] = $fileRules;
        }
EOT;
        }

        $rules .= "\r\n return \$rules;\r\n";

        return str_replace(':rules:',
            $rules,
            $this->stub
        );
    }

    private function getView (): string
    {
        return str_replace(':view:',
            Text::config($this->config)->renderView('view'),
            $this->stub
        );
    }

    private function getProperties (): string
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
        foreach ($fields as $field)
        {
            $response .= "public Collection $" . $field['relationships']['table'] . ";" . PHP_EOL;
        }
        return str_replace(':properties:', $response, $this->stub);
    }

    private function getUseCollection (): string
    {
        $response = '';
        if ( ! empty(Field::config($this->config)->getRelationships()))
        {
            $response = 'use Illuminate\Support\Collection;';
        }

        return str_replace(':useCollection:', $response, $this->stub);
    }

    private function getQueryRelationships (): string
    {
        $response = '';
        $fields = Field::config($this->config)->getRelationships();
        foreach ($fields as $field)
        {
            $response .= "\$this->" . $field['relationships']['table'] . " = " . $field['relationships']['model'] . "::all();" .
                PHP_EOL;
        }
        return str_replace(':queryRelationships:', $response, $this->stub);
    }
}
