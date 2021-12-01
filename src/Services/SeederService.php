<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;

class SeederService
{
    private string $databaseSeeder = 'database/seeders/DatabaseSeeder.php';

    public function add(array $config)
    {
        $content = File::get($this->databaseSeeder);

        $content = $this->addUse($config, $content);

<<<<<<< HEAD
        $factory = Text::config($config)->name('model') . "::factory(10)->create()";

        foreach ($config['fields'] as $field)
        {
            if (isset($field['relationship']) and $field['relationship']['type'] === 'belongsToMany')
            {
                $factory .= "->each(function(\$model) { \$model->" . $field['relationship']['name']
                    . "()->attach(" . $field['relationship']['model'] . "::factory(3)->create()); })";
            }
        }

        if ( ! str_contains($content, $factory))
        {
=======
        if (! str_contains($content, $use)) {
            $content = str_replace(
                "Seeders;",
                "Seeders;" . PHP_EOL . "use " . $use . PHP_EOL,
                $content
            );
        }

        $factory = Text::config($config)->name('model') . "::factory(10)->create();";

        if (! str_contains($content, $factory)) {
>>>>>>> 5d36defe2c536482610e31c26878e25028cc7f16
            $content = str_replace(
                "User::factory(10)->create();",
                "User::factory(10)->create();" . PHP_EOL . $factory . ';' . PHP_EOL,
                $content
            );
        }

        File::put($this->databaseSeeder, $content);
    }

<<<<<<< HEAD
    private function addUse (array $config, string $content): string|array
    {
        $useModel = "use " . Text::config($config)->namespace('model');
        $use = str_contains($content, $useModel)
            ? ''
            : $useModel . PHP_EOL;

        foreach ($config['fields'] as $field)
        {
            if (isset($field['relationship']) and $field['relationship']['type'] === 'belongsToMany')
            {
                $useRelationship = "use " . $field['relationship']['namespace'] . $field['relationship']['model'] . ';';

                $use .= str_contains($content, $useRelationship)
                    ? ''
                    : $useRelationship . PHP_EOL;
            }
        }

        return str_replace(
            "Seeders;",
            "Seeders;" . PHP_EOL . $use,
            $content
        );
    }

    public function delete (array $config)
=======
    public function delete(array $config)
>>>>>>> 5d36defe2c536482610e31c26878e25028cc7f16
    {
        $rows = explode(';', File::get($this->databaseSeeder));

        $content = '';
        foreach ($rows as $row) {
            if (str_contains($row, Text::config($config)->name('model'))) {
                $content .= str_contains($row, '<?php') ? '<?php' : '';
            } else {
                $content .= $row . ';';
            }
        }

        File::put($this->databaseSeeder, $content);
    }
}
