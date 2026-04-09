<?php

namespace Test\Lucinda\MVC\Service;

use Lucinda\MVC\Service\ResolverInfoDetector;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoApplication;
use Test\Lucinda\MVC\Support\DemoRequestValidator;
use Test\Lucinda\MVC\Support\DemoViewResolver;

class ResolverInfoDetectorTest
{
    public function getResolver()
    {
        $application = new DemoApplication(dirname(__DIR__)."/fixtures/root.xml");
        $detector = new ResolverInfoDetector($application, new DemoRequestValidator("index", "json"));
        $resolver = $detector->getResolver();

        return [
            (new Strings($resolver->getFormat()))->assertEquals("json"),
            (new Strings($resolver->getViewResolver()))->assertEquals(DemoViewResolver::class)
        ];
    }
}
