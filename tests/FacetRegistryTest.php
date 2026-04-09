<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\FacetException;
use Lucinda\MVC\FacetRegistry;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoAliasedFacet;
use Test\Lucinda\MVC\Support\DemoFacet;
use Test\Lucinda\MVC\Support\DemoFacetAlias;

class FacetRegistryTest
{
    public function put()
    {
        $results = [];
        $registry = new FacetRegistry();
        $facet = new DemoFacet();
        $registry->put($facet);

        $results[] = (new Objects($registry->get(DemoFacet::class)))->assertInstanceOf(DemoFacet::class);

        try {
            $registry->put(new DemoFacet());
            $results[] = (new Strings(""))->assertNotEmpty("duplicate facet should fail");
        } catch (FacetException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("only be injected once");
        }

        return $results;
    }

    public function putAs()
    {
        $results = [];
        $registry = new FacetRegistry();
        $facet = new DemoAliasedFacet();
        $registry->putAs(DemoFacetAlias::class, $facet);

        $results[] = (new Objects($registry->get(DemoFacetAlias::class)))->assertInstanceOf(DemoAliasedFacet::class);

        try {
            $registry->putAs(DemoFacetAlias::class, new DemoAliasedFacet());
            $results[] = (new Strings(""))->assertNotEmpty("duplicate alias should fail");
        } catch (FacetException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("only be injected once");
        }

        return $results;
    }

    public function get()
    {
        $registry = new FacetRegistry();
        $facet = new DemoFacet();
        $registry->put($facet);
        return new Booleans($registry->get(DemoFacet::class) === $facet)->assertTrue();
    }

    public function has()
    {
        $registry = new FacetRegistry();
        $registry->put(new DemoFacet());
        return [
            (new Booleans($registry->has(DemoFacet::class)))->assertTrue(),
            (new Booleans($registry->has("missing")))->assertFalse()
        ];
    }
}
