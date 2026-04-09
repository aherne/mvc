<?php

namespace Test\Lucinda\MVC\XmlReader;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ElementTest
{
    private Element $element;

    public function __construct()
    {
        $this->element = new Element(
            simplexml_load_string('<root id="123"><child name="first"/><child name="second"/></root>')
        );
    }

    public function getName()
    {
        return new Strings($this->element->getName())->assertEquals("root");
    }

    public function getAttributes()
    {
        return [
            (new Arrays($this->element->getAttributes()))->assertContainsKey("id"),
            (new Strings($this->element->getAttributes()["id"]))->assertEquals("123")
        ];
    }

    public function getChildren()
    {
        $children = $this->element->getChildren();
        return [
            (new Arrays($children))->assertContainsKey("child"),
            (new Arrays($children["child"]))->assertSize(2),
            (new Objects($children["child"][0]))->assertInstanceOf(Element::class)
        ];
    }
}
