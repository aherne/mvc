<?php
namespace Lucinda\MVC\FacetsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;
use Lucinda\MVC\Facets\RouteInfo;

class RoutesList implements XmlList
{
    final public function convert(Element $element): array
    {
        $output = [];
        $list = $element->getChildren();
        if (empty($list["route"])) {
            throw new Exception("At least one 'route' tag is mandatory for 'routes' children");
        }
        $facetClass = $this->getFacetClass();
        foreach ($list["route"] as $info) {
            $info = new $facetClass($element);
            $output[$info->getID()] = $info;
        }
        return $output;
    }
    
    protected function getFacetClass(): string
    {
        return RouteInfo::class;
    }
}