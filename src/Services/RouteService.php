<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class RouteService
{
    protected const ROUTES_WEB_PHP = 'routes/web.php';

    public function add (array $config)
    {
        $this->delete($config);

        $content = File::get(self::ROUTES_WEB_PHP);

        $content = str_replace(
            "?php",
            "?php" . PHP_EOL . "use " . gen()->namespace()->withFile()->livewire($config) . PHP_EOL,
            $content
        );
        $content = $content . PHP_EOL . $this->getRoute($config) . PHP_EOL;

        $content = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $content);

        File::put(self::ROUTES_WEB_PHP, $content);
    }

    public function delete (array $config)
    {
        $content = File::get(self::ROUTES_WEB_PHP);

        $content = str_replace('use ' . gen()->namespace()->withFile()->livewire($config), '', $content);

        $content = str_replace($this->getRoute($config), '', $content);

        $content = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $content);

        File::put(self::ROUTES_WEB_PHP, $content);
    }

    protected function getRoute (array $config): string
    {
        $route = "Route::get('/" . $config['route']['path'] . "/{parameterAction?}/{parameterId?}', "
            . gen()->config()->livewire($config) . "::class)";
        $route .= gen()->config()->isBackend($config) ? "->middleware('auth')->prefix('sistema')" : "";
        $route .= "->name('{$config['table']['name']}');";

        return $route;
    }
}
