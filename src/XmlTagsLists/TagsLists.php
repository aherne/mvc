<?php
namespace Lucinda\MVC\XmlTagsLists;

abstract class TagsLists
{
    protected string $facetClass;

    public function __construct(string $facetClass)
    {
        $this->facetClass = $facetClass;
    }
}
