<?php

namespace Dantofema\LaravelSetup\Services;

class GenerateService
{
    public function delete (array $config, array $types)
    {
        (new DeleteService())->execute($config, $types);
    }

    public function addRoute (array $config)
    {
        (new RouteService())->add($config);
    }

    public function removeRoute (array $config)
    {
        (new RouteService())->delete($config);
    }

    public function addSeeder (array $config)
    {
        (new SeederService())->add($config);
    }

    public function removeSeeder (array $config)
    {
        (new SeederService())->delete($config);
    }

    public function setup ()
    {
        (new BeforeService())->setup();
    }

    public function replaceFromConfig (array $config, string $type, string $stub): string
    {
        return (new ReplaceService())->fromConfig($config, $type, $stub);
    }

    public function replaceFromField (array $field, array $config, string $stub): string
    {
        return (new ReplaceService())->fromField($field, $config, $stub);
    }

    public function getStub (string $stub): string
    {
        return (new StubService())->get($stub);
    }

    public function getName (array $config, string $type, bool $whitExtension = false): string
    {
        return (new NameService())->get($config, $type, $whitExtension);
    }

    public function getPath (array $config, string $type): string
    {
        return (new PathService())->get($config, $type);
    }

    public function getNamespace (array $config, string $type, bool $whitName = false): string
    {
        return (new PathService())->namespace($config, $type, $whitName);
    }

    public function getRenderView (array $config): string
    {
        return (new PathService())->renderView($config);
    }

}
