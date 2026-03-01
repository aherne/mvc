<?php

namespace Lucinda\MVC\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Controller
{
    public function __construct(
        public readonly ?string $name = null
        ) {}
}