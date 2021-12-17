<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class GenerateService
{
    #[Pure] public function delete (): DeleteService
    {
        return new DeleteService();
    }

    #[Pure] public function route (): RouteService
    {
        return new RouteService();
    }

    #[Pure] public function seeder (): SeederService
    {
        return new SeederService();
    }

    public function setup ()
    {
        (new BeforeService())->setup();
    }

    #[Pure] public function stub (): StubService
    {
        return new StubService();
    }

    #[Pure] public function config (): ConfigService
    {
        return new ConfigService();
    }

    #[Pure] public function field (): FieldService
    {
        return new FieldService();
    }

    #[Pure] public function path (): PathService
    {
        return new PathService();
    }

    #[Pure] public function namespace (): NamespaceService
    {
        return new NamespaceService();
    }
}
