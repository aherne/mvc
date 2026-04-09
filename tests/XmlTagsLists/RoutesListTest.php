<?php

namespace Test\Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\RouteInfo;
use Lucinda\MVC\XmlTagsLists\RoutesList;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;

class RoutesListTest
{
    public function convert()
    {
        $element = new Element(
            simplexml_load_string(
                '<routes>
                    <route id="index" controller="Test\Lucinda\MVC\Support\DemoViewAwareController" view="home" format="json"/>
                    <route id="users" controller="Test\Lucinda\MVC\Support\DemoViewUnawareController" format="json"/>
                </routes>'
            )
        );

        $results = (new RoutesList(RouteInfo::class))->convert($element);

        return [
            (new Arrays($results))->assertSize(2),
            (new Arrays($results))->assertContainsKey("index"),
            (new Objects($results["index"]))->assertInstanceOf(RouteInfo::class)
        ];
    }
}
