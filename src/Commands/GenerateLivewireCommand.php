<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLivewireCommand extends Command

{
    use CommandTrait;

    protected const STUB_PATH = '/../Stubs/Livewire.php.stub';
    protected const DIRECTORY = 'app/Http/Livewire';
    public $signature = 'generate:livewire {path : path to the config file } {--force}';
    public $description = 'Model file generator';
    protected array $config;

    /**
     * @throws Exception
     */
    public function handle (): bool
    {
        File::ensureDirectoryExists(self::DIRECTORY . '/Backend/');
        File::ensureDirectoryExists(self::DIRECTORY . '/Frontend/');

        $this->init('livewire');
        return $this->create();
    }

    public function create (): bool
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
        $this->stub = str_replace(
            ':modelName:',
            ucfirst($this->config['model']['name']),
            $this->stub
        );
        $this->stub = $this->sortField();
        $this->stub = $this->getNewImage();
        $this->stub = $this->getEditing();
        $this->stub = $this->getDetach();
        $this->stub = $this->getModelArgument();
        $this->stub = str_replace(':varModel:', $this->getVariableModel(), $this->stub);
        $this->stub = $this->getEditNewImage();
        $this->stub = $this->getCreateNewImage();
        $this->stub = $this->getSaveNewImage();
        $this->stub = $this->getSaveSlug();
        $this->stub = $this->getRules();
        $this->stub = $this->getView();
        return $this->stub;
    }

    private function getNamespace (): string
    {
        return str_replace(
            ':namespace:',
            $this->config['livewire']['namespace'],
            $this->stub
        );
    }

    private function getUseModels (): string
    {
        $response = '';
        foreach ($this->config['livewire']['useModels'] as $item)
        {
            $response .= "use $item;\r\n";
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

    private function getNewImage (): string
    {
        return str_replace(':newImage:',
            $this->newImageExist() ? 'public $newImage;' : '',
            $this->stub
        );
    }

    private function newImageExist (): bool
    {
        return array_key_exists('newImage', $this->config['livewire']['properties']);
    }

    private function getEditing (): string
    {
        return str_replace(':editing:',
            $this->config['model']['name'],
            $this->stub);
    }

    private function getDetach (): string
    {
        $response = '';
        foreach ($this->config['model']['relationships']['belongsToMany'] as $relation)
        {
            $response .= "\$this->editing->$relation[0]()->detach();\r\n";
        }
        return str_replace(':detach:', $response, $this->stub);
    }

    private function getModelArgument (): string
    {
        $response = $this->getModelName() . ' ' . $this->getVariableModel();
        return str_replace(':modelArgument:', $response, $this->stub);
    }

    public function getEditNewImage (): string
    {
        return str_replace(':editNewImage:',
            $this->newImageExist() ? '$this->newImage = "";' : '',
            $this->stub
        );
    }

    public function getCreateNewImage (): string
    {
        return str_replace(':createNewImage:',
            $this->newImageExist() ? '$this->newImage = null;' : '',
            $this->stub
        );
    }

    public function getSaveNewImage (): string
    {
        if ($this->newImageExist())
        {
            $field = $this->config['livewire']['properties']['newImage']['field'];
            $disk = $this->config['livewire']['properties']['newImage']['disk'];

            return str_replace(':saveNewImage:',
                "\$this->setNewImage('$field', '$disk');",
                $this->stub
            );
        }

        return str_replace(':saveNewImage:',
            "",
            $this->stub
        );
    }

    public function getSaveSlug (): string
    {
        $field = $this->config['livewire']['save']['slug'];
        return str_replace(':saveSlug:',
            $this->newImageExist() ? "\$this->setSlug('$field');" : '',
            $this->stub
        );
    }

    private function getRules (): string
    {
        $rules = "\$rules = [\r\n";
        foreach ($this->config['livewire']['rules'] as $key => $rule)
        {
            $rules .= "'$key' => '$rule',\r\n";
        }
        $rules .= "];\r\n";

        if ($this->newImageExist())
        {
            $newImageRule = '';
            foreach ($this->config['table']['columns'] as $column)
            {
                if (in_array('image', $column))
                {
                    $newImageRule .= count($column) == 2 ? "|required" : '';
                    $newImageRule .= in_array('nullable', $column) ? "|nullable" : '';
                }
            }
            $rules .= <<<EOT
        if (\$this->createAction)
        {
            \$rules['newImage'] = 'image$newImageRule';
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
            $this->config['livewire']['view'],
            $this->stub
        );
    }

}
