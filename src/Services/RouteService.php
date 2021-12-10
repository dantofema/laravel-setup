<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class RouteService
{
    protected const ROUTES_WEB_PHP = 'routes/web.php';

    public function add (array $config)
    {
        $content = File::get(self::ROUTES_WEB_PHP);

        if (str_contains($content, gen()->getNamespace($config, 'livewire')))
        {
            $content = str_replace(
                "?php",
                "?php\r\nuse " . gen()->getNamespace($config, 'livewire') . PHP_EOL,
                $content
            );

            $content = str_replace(
                "\r\n\r\n",
                "",
                $content
            );
        }

        File::put(self::ROUTES_WEB_PHP, $content . $this->getRoute($config) . PHP_EOL);
    }

    protected function getRoute (array $config): string
    {
        $route = "\r\nRoute::get('/" . $config['route']['path'] . "', " . gen()->getName($config, 'livewire') . "::class)";
        $route .= $config['backend'] ? "->middleware('auth')->prefix('sistema')" : "";
        $route .= "->name('{$config['table']['name']}');";

        return $route;
    }

    public function delete (array $config)
    {
        $content = File::get(self::ROUTES_WEB_PHP);
        $content = str_replace('use ' . gen()->getNamespace($config, 'livewire'), '', $content);

        $content = str_replace($this->getRoute($config), '', $content);

        File::put(self::ROUTES_WEB_PHP, $content);
    }
}
