<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\EventListener\MultiFaceted;
use Lucinda\MVC\FacetCollection;

final class DemoMultiFacetedListener implements MultiFaceted
{
    public function run(): FacetCollection
    {
        $collection = new FacetCollection();
        $collection->add("demo", new DemoFacet());
        return $collection;
    }
}
