<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\EventListener\UnFaceted;
use Lucinda\MVC\Response\Transformer\Transformer;

final class DemoTransformerListener implements UnFaceted, Transformer
{
    public function run(): void
    {
    }
}
