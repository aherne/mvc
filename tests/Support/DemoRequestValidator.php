<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\RequestValidator;

final class DemoRequestValidator implements RequestValidator
{
    public function __construct(
        private string $route,
        private string $format
    ) {
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
