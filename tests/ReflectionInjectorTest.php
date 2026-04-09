<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\FacetException;
use Lucinda\MVC\FacetRegistry;
use Lucinda\MVC\ReflectionInjector;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoAliasedFacet;
use Test\Lucinda\MVC\Support\DemoFacet;
use Test\Lucinda\MVC\Support\DemoFacetAlias;
use Test\Lucinda\MVC\Support\NeedsAliasedFacet;
use Test\Lucinda\MVC\Support\NeedsBuiltinParameter;
use Test\Lucinda\MVC\Support\NeedsDemoFacet;
use Test\Lucinda\MVC\Support\NeedsMissingFacet;
use Test\Lucinda\MVC\Support\NoDependencies;

class ReflectionInjectorTest
{
    public function create()
    {
        $results = [];

        $registry = new FacetRegistry();
        $injector = new ReflectionInjector($registry);
        $results[] = (new Objects($injector->create(NoDependencies::class)))->assertInstanceOf(NoDependencies::class);

        $registry = new FacetRegistry();
        $facet = new DemoFacet();
        $registry->put($facet);
        $injector = new ReflectionInjector($registry);
        $object = $injector->create(NeedsDemoFacet::class);
        $results[] = (new Objects($object))->assertInstanceOf(NeedsDemoFacet::class);
        $results[] = (new Booleans($object->facet === $facet))->assertTrue();

        $registry = new FacetRegistry();
        $aliasedFacet = new DemoAliasedFacet();
        $registry->putAs(DemoFacetAlias::class, $aliasedFacet);
        $injector = new ReflectionInjector($registry);
        $object = $injector->create(NeedsAliasedFacet::class);
        $results[] = (new Booleans($object->facet === $aliasedFacet))->assertTrue();

        try {
            (new ReflectionInjector(new FacetRegistry()))->create(NeedsBuiltinParameter::class);
            $results[] = (new Strings(""))->assertNotEmpty("builtin parameter should fail");
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("must be a class/interface type");
        }

        try {
            (new ReflectionInjector(new FacetRegistry()))->create(NeedsMissingFacet::class);
            $results[] = (new Strings(""))->assertNotEmpty("missing facet should fail");
        } catch (FacetException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("Facet not available");
        }

        return $results;
    }
}
