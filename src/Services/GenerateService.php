<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class GenerateService
{
    public function delete (array $config, array $types)
    {
        (new DeleteService())->execute($config, $types);
    }

    public function addRoute (array $config, string $type)
    {
        (new RouteService())->add($config, $type);
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

    public function getStub (string $type): string
    {
        return (new StubService())->get($type);
    }

    public function getName (array $config, string $type, bool $whitExtension = false): string
    {
        return (new NameService())->get($config, $type, $whitExtension);
    }

    #[Pure] public function getRoute (): string
    {
        return (new PathService())->route();
    }

    public function getPath (array $config, string $type): string
    {
        return (new PathService())->get($config, $type);
    }

    public function getNamespace (array $config, string $type, bool $whitName = false): string
    {
        return (new PathService())->namespace($config, $type, $whitName);
    }

    public function getRenderView (array $config, string $type): string
    {
        return (new PathService())->renderView($config, $type);
    }

    #[Pure] public function field (): FieldService
    {
        return new FieldService();
    }

    #[Pure] public function name (): NameService
    {
        return new NameService();
    }

    #[Pure] public function path (): PathService
    {
        return new PathService();
    }

}
