<?php

namespace Test\Lucinda\MVC\XmlTags;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\RouteInfo;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoViewAwareController;

class RouteInfoTest
{
    private RouteInfo $object;

    public function __construct()
    {
        $this->object = new RouteInfo(
            new Element(
                simplexml_load_string(
                    '<route id="index" controller="Test\Lucinda\MVC\Support\DemoViewAwareController" view="home" format="json"/>'
                )
            )
        );
    }

    public function getID()
    {
        return new Strings($this->object->getID())->assertEquals("index");
    }

    public function getController()
    {
        return new Strings($this->object->getController())->assertEquals(DemoViewAwareController::class);
    }

    public function getView()
    {
        return new Strings($this->object->getView())->assertEquals("home");
    }

    public function getFormat()
    {
        return new Strings($this->object->getFormat())->assertEquals("json");
    }
}
