<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;

class SeederService
{
    private string $databaseSeeder = 'database/seeders/DatabaseSeeder.php';

    public function add (array $config)
    {
        $content = File::get($this->databaseSeeder);

        $use = Text::config($config)->namespace('model');

        if ( ! str_contains($content, $use))
        {
            $content = str_replace(
                "Seeders;",
                "Seeders;" . PHP_EOL . "use " . $use . PHP_EOL,
                $content
            );
        }

        $factory = Text::config($config)->name('model') . "::factory(10)->create();";

        if ( ! str_contains($content, $factory))
        {
            $content = str_replace(
                "User::factory(10)->create();",
                "User::factory(10)->create();\r\n" . $factory . "\r\n",
                $content
            );
        }

        File::put($this->databaseSeeder, $content);
    }

    public function delete (array $config)
    {
        $rows = explode(';', File::get($this->databaseSeeder));

        $content = '';
        foreach ($rows as $row)
        {
            if (str_contains($row, Text::config($config)->name('model')))
            {
                $content .= str_contains($row, '<?php') ? '<?php' : '';
            } else
            {
                $content .= $row . ';';
            }
        }

        File::put($this->databaseSeeder, $content);
    }

}