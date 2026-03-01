<?php
namespace Lucinda\MVC\FacetsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;
use Lucinda\MVC\Facets\ResolverInfo;

class ResolversList implements XmlList
{
    final public function convert(Element $element): array
    {
        $output = [];
        $list = $element->getChildren();
        if (empty($list["resolver"])) {
            throw new Exception("At least one 'resolver' tag is mandatory for 'resolvers' children");
        }
        $facetClass = $this->getFacetClass();
        foreach ($list["resolver"] as $info) {
            $info = new $facetClass($element);
            $output[$info->getFormat()] = $info;
        }
        return $output;
    }
    
    protected function getFacetClass(): string
    {
        return ResolverInfo::class;
    }
}
