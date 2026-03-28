<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlTags\XmlElementInfo;

/**
 * Defines blueprint of turning a list of tags into an array of Element parsers
 */
interface XmlList
{
    /**
     * Converts Element received (encapsulating a parsed XML tag) into an array
     * 
     * @param Element $element
     * @return array<string,XmlElementInfo>
     */
    function convert(Element $element): array;
}
