<?php

use Dantofema\LaravelSetup\Services\GenerateService;
use JetBrains\PhpStorm\Pure;

#[Pure] function gen (): GenerateService
{
    return new GenerateService();
}
