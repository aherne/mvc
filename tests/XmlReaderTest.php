<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\XmlReader;
use Lucinda\MVC\XmlReader\Element;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class XmlReaderTest
{
    public function getTag()
    {
        $reader = new XmlReader(__DIR__."/fixtures/root.xml");
        $tag = $reader->getTag("application");

        return [
            (new Objects($tag))->assertInstanceOf(Element::class),
            (new Strings($tag->getName()))->assertEquals("application")
        ];
    }

    public function hasTag()
    {
        $reader = new XmlReader(__DIR__."/fixtures/root.xml");
        return [
            (new Booleans($reader->hasTag("application")))->assertTrue(),
            (new Booleans($reader->hasTag("missing")))->assertFalse()
        ];
    }
}
