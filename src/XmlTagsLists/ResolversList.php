<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;
use Lucinda\MVC\XmlTags\ResolverInfo;

/**
 * Detects view resolvers from <resolvers> XML tag
 */
final class ResolversList extends TagsLists implements XmlList
{
    /**
     * Converts Element received (encapsulating a parsed XML tag) into an array
     * 
     * @param Element $element
     * @return array<string,ResolverInfo>
     */
    public function convert(Element $element): array
    {
        $output = [];
        $list = $element->getChildren();
        if (empty($list["resolver"])) {
            throw new Exception("At least one 'resolver' tag is mandatory for 'resolvers' children");
        }
        $facetClass = $this->tagClass;
        foreach ($list["resolver"] as $info) {
            $info = new $facetClass($element);
            $output[$info->getFormat()] = $info;
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
        return ResolverInfo::class;
    }
}
