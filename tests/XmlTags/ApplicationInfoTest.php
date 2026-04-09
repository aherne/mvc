<?php

namespace Test\Lucinda\MVC\XmlTags;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\ApplicationInfo;
use Lucinda\UnitTest\Validator\Strings;

class ApplicationInfoTest
{
    private ApplicationInfo $object;

    public function __construct()
    {
        $this->object = new ApplicationInfo(
            new Element(
                simplexml_load_string(
                    '<application default_format="json" default_route="index" views_folder="views" views_extension="phtml" version="1.0.0"/>'
                )
            )
        );
    }

    public function getDefaultFormat()
    {
        return new Strings($this->object->getDefaultFormat())->assertEquals("json");
    }

    public function getDefaultRoute()
    {
        return new Strings($this->object->getDefaultRoute())->assertEquals("index");
    }

    public function getViewsFolder()
    {
        return new Strings($this->object->getViewsFolder())->assertEquals("views");
    }

    public function getViewsExtension()
    {
        return new Strings($this->object->getViewsExtension())->assertEquals("phtml");
    }

    public function getVersion()
    {
        return new Strings($this->object->getVersion())->assertEquals("1.0.0");
    }
}
