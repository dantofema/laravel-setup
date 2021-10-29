<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;

class RouteService
{

    protected const ROUTES_WEB_PHP = 'routes/web.php';

    public function add (array $config)
    {
        $haystack = File::get(self::ROUTES_WEB_PHP);
        $namespace = Text::config($config)->namespace('livewire');
        $needle = "?php";
        $replace = "?php\r\nuse $namespace;\r\n";
        $pos = strpos($haystack, $needle);

        if ($pos !== false)
        {
            $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
        }

        $livewire = Text::config($config)->name('livewire');
        $route = $this->routeGet($config['model']['path'], $livewire);
        $route .= $this->routeMiddleware($config['backend']);
        $route .= "->name('{$config['table']['name']}');\r\n";

        File::put(self::ROUTES_WEB_PHP, $haystack . $route);
    }

    protected function routeGet ($path, $livewire): string
    {
        return "\r\nRoute::get('/$path', $livewire::class)";
    }

    protected function routeMiddleware ($backend): string
    {
        return $backend ? "->middleware('auth')->prefix('sistema')" : "";
    }

    public function delete (array $config)
    {
        $rows = explode(';', File::get(self::ROUTES_WEB_PHP));
        $livewire = Text::config($config)->name('livewire');
        foreach ($rows as $key => $row)
        {
            if (str_contains($row, $livewire))
            {
                unset($rows[$key]);
            }
        }

        $content = implode(';', $rows);

        File::put(self::ROUTES_WEB_PHP, $content);
    }
}