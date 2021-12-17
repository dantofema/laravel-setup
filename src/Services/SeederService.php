<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class SeederService
{
    private string $databaseSeeder = 'database/seeders/DatabaseSeeder.php';
    private string $searchString = "User::factory(10)->create();";

    public function add (array $config)
    {
        $content = File::get($this->databaseSeeder);

        $content = $this->addUse($config, $content);
        $factory = gen()->config()->model($config) . "::factory(10)->create()";

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
            $content = str_replace(
                $this->searchString,
                $factory . ';' . PHP_EOL . $this->searchString,
                $content
            );
        }

        File::put($this->databaseSeeder, $content);
    }

    private function addUse (array $config, string $content): string|array
    {
        $useModel = "use " . gen()->namespace()->withFile()->model($config);
        $use = str_contains($content, $useModel)
            ? ''
            : $useModel . PHP_EOL;

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isBelongsToMany($field))
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
    {
        $rows = explode(PHP_EOL, File::get($this->databaseSeeder));
        $content = '';
        foreach ($rows as $row)
        {
            if (
                str_contains($row, gen()->config()->model($config)) and
                ! str_contains($row, 'User::factory')
            )
            {
                $content .= str_contains($row, '<?php') ? '<?php' : '';
            } else
            {
                $content .= $row . PHP_EOL;
            }
        }

        File::put($this->databaseSeeder, $content);
    }
}
