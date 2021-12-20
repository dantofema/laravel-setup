<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class SeederService
{
    private string $pathDatabaseSeeder = 'database/seeders/DatabaseSeeder.php';
    private string $searchString = "User::factory(10)->create();";

    public function add (array $config)
    {
        $content = File::get($this->pathDatabaseSeeder);

        $content = $this->addUse($config, $content);
        $factory = gen()->config()->model($config) . "::factory(10)->create();";

        if ( ! str_contains($content, $factory))
        {
            $content = str_replace(
                $this->searchString,
                $factory . PHP_EOL . $this->searchString,
                $content
            );
        }

        $content = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $content);
        File::put($this->pathDatabaseSeeder, $content);
    }

    private function addUse (array $config, string $content): string|array
    {
        $useModel = "use " . gen()->namespace()->withFile()->model($config);
        $use = str_contains($content, $useModel)
            ? ''
            : $useModel . PHP_EOL;

        return str_replace(
            "Seeders;",
            "Seeders;" . PHP_EOL . $use,
            $content
        );
    }

    public function delete (array $config)
    {
        $rows = explode(PHP_EOL, File::get($this->pathDatabaseSeeder));
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
        $content = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $content);
        File::put($this->pathDatabaseSeeder, $content);
    }
}
