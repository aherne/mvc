<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;
use Lucinda\MVC\XmlTags\RouteInfo;

/**
 * Detects routes from <routes> XML tag
 */
final class RoutesList extends TagsLists implements XmlList
{
    /**
     * Converts Element received (encapsulating a parsed XML tag) into an array
     * 
     * @param Element $element
     * @return array<string,RouteInfo>
     */
    public function convert(Element $element): array
    {
        $output = [];
        $list = $element->getChildren();
        if (empty($list["route"])) {
            throw new Exception("At least one 'route' tag is mandatory for 'routes' children");
        }
        $facetClass = $this->tagClass;
        foreach ($list["route"] as $info) {
            $info = new $facetClass($element);
            $output[$info->getID()] = $info;
        }
        return $output;
    }

    /**
     * Gets class expected to handle the subtag
     * 
     * @return string
     */
    protected function getChildTagBaseClass(): string
    {
        return RouteInfo::class;
    }
}