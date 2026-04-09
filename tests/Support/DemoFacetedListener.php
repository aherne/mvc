<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\EventListener\Faceted;
use Lucinda\MVC\Facet;

final class DemoFacetedListener implements Faceted
{
    public function run(): Facet
    {
        return new DemoFacet();
    }
}
