<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\Response\Transformer\Body;

final class DemoBodyTransformer implements Body
{
    public function transform(string $source): string
    {
        return strtoupper($source);
    }
}
