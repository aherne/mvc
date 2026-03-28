<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

final class RoutesList extends TagsLists implements XmlList
{
    public function convert(Element $element): array
    {
        $output = [];
        $list = $element->getChildren();
        if (empty($list["route"])) {
            throw new Exception("At least one 'route' tag is mandatory for 'routes' children");
        }
        $facetClass = $this->facetClass;
        foreach ($list["route"] as $info) {
            $info = new $facetClass($element);
            $output[$info->getID()] = $info;
        }
        return $output;
    }
}