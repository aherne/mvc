<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\FacetCollection;
use Lucinda\MVC\FacetException;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoFacet;

class FacetCollectionTest
{
    public function add()
    {
        $collection = new FacetCollection();
        $facet = new DemoFacet();
        $collection->add("demo", $facet);
        $all = $collection->getAll();

        return [
            (new Arrays($all))->assertContainsKey("demo"),
            (new Objects($all["demo"]))->assertInstanceOf(DemoFacet::class)
        ];
    }

    public function getAll()
    {
        $results = [];

        try {
            (new FacetCollection())->getAll();
            $results[] = (new Strings(""))->assertNotEmpty("empty collection should fail");
        } catch (FacetException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("cannot be empty");
        }

        $collection = new FacetCollection();
        $collection->add("demo", new DemoFacet());
        $results[] = (new Arrays($collection->getAll()))->assertSize(1);

        return $results;
    }
}
