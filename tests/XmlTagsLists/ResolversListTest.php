<?php

namespace Test\Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\ResolverInfo;
use Lucinda\MVC\XmlTagsLists\ResolversList;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;

class ResolversListTest
{
    public function convert()
    {
        $element = new Element(
            simplexml_load_string(
                '<resolvers>
                    <resolver format="json" class="Test\Lucinda\MVC\Support\DemoViewResolver"/>
                    <resolver format="html" class="Test\Lucinda\MVC\Support\DemoViewResolver"/>
                </resolvers>'
            )
        );

        $results = (new ResolversList(ResolverInfo::class))->convert($element);

        return [
            (new Arrays($results))->assertSize(2),
            (new Arrays($results))->assertContainsKey("json"),
            (new Objects($results["json"]))->assertInstanceOf(ResolverInfo::class)
        ];
    }
}
