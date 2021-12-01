<?php

namespace Dantofema\LaravelSetup\Services;

class GenerateService
{
    public function delete(array $config, string $type)
    {
        (new DeleteService())->type($type)->config($config);
    }

    public function addRoute(array $config)
    {
        (new RouteService())->add($config);
    }

    public function removeRoute(array $config)
    {
        (new RouteService())->delete($config);
    }

    public function addSeeder(array $config)
    {
        (new SeederService())->add($config);
    }

    public function removeSeeder(array $config)
    {
        (new SeederService())->delete($config);
    }

    public function setup()
    {
        (new BeforeService())->setup();
    }

    public function replaceDefault(array $config, string $stub, string $type)
    {
        (new ReplaceService())->config($config)->stub($stub)->type($type)->default();
    }

    public function replaceField(array $config, string $stub, array $field)
    {
        (new ReplaceService())->config($config)->stub($stub)->field($field);
    }
}
