<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;

class RouteService
{

    protected const ROUTES_WEB_PHP = 'routes/web.php';

    public function add (array $config)
    {
        $content = File::get(self::ROUTES_WEB_PHP);

        $content = str_replace(
            "?php",
            "?php\r\nuse " . Text::config($config)->namespace('livewire') . "\r\n", $content
        );

        $content = str_replace(
            "\r\n\r\n",
            "", $content
        );

        File::put(self::ROUTES_WEB_PHP, $content . $this->getRoute($config) . "\r\n");
    }

    protected function getRoute (array $config): string
    {
        $route = "\r\nRoute::get('/" . $config['route']['path'] . "', " . Text::config($config)->name('livewire') . "::class)";
        $route .= $config['backend'] ? "->middleware('auth')->prefix('sistema')" : "";
        $route .= "->name('{$config['table']['name']}');";
        return $route;
    }

    public function delete (array $config)
    {
        $content = File::get(self::ROUTES_WEB_PHP);
        $content = str_replace('use ' . Text::config($config)->namespace('livewire'), '', $content);

        $content = str_replace($this->getRoute($config), '', $content);

        File::put(self::ROUTES_WEB_PHP, $content);
    }
}