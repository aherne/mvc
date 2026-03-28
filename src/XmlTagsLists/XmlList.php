<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\XmlReader\Element;

interface XmlList
{
    function convert(Element $element): array;
}
