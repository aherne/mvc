<?php

namespace Test\Lucinda\MVC\XmlTags;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\ResolverInfo;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoViewResolver;

class ResolverInfoTest
{
    private ResolverInfo $object;

    public function __construct()
    {
        $this->object = new ResolverInfo(
            new Element(
                simplexml_load_string(
                    '<resolver format="json" class="Test\Lucinda\MVC\Support\DemoViewResolver"/>'
                )
            )
        );
    }

    public function getFormat()
    {
        return new Strings($this->object->getFormat())->assertEquals("json");
    }

    public function getViewResolver()
    {
        return new Strings($this->object->getViewResolver())->assertEquals(DemoViewResolver::class);
    }
}
