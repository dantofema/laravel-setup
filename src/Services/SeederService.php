<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;

class SeederService
{
    private string $databaseSeeder = 'database/seeders/DatabaseSeeder.php';

    public function add (array $config)
    {
        $haystack = File::get($this->databaseSeeder);
        $needle = ";";
        $replace = ";\r\nuse " . Text::config($config)->namespace('model') . "\r\n";
        $pos = strpos($haystack, $needle);

        if ($pos !== false)
        {
            $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
        }
        $needle = "create();";
        $replace = "create();\r\n";
        $replace .= Text::config($config)->name('model') . "::factory(10)->create();\r\n";
        $pos = strpos($haystack, $needle);

        if ($pos !== false)
        {
            $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
        }

        File::put($this->databaseSeeder, $haystack);
    }

    public function delete (array $config)
    {
        $rows = explode(';', File::get($this->databaseSeeder));

        foreach ($rows as $key => $row)
        {
            if (str_contains($row, Text::config($config)->name('seeder')))
            {
                unset($rows[$key]);
            }
        }

        $content = implode('', $rows);

        File::put($this->databaseSeeder, $content);
    }
}