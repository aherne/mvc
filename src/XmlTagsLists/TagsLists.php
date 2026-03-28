<?php
namespace Lucinda\MVC\XmlTagsLists;

use Lucinda\MVC\ConfigurationException;

/**
 * Implements the blueprint for XML tag list traversal, where each sub-tag is mapped by a class
 */
abstract class TagsLists
{
    protected string $tagClass;

    /**
     * Sets class that will maps the XML tag
     * 
     * @param string $tagClass
     */
    public function __construct(string $tagClass)
    {
        $expectedBaseClass = $this->getChildTagBaseClass();
        if (!is_subclass_of($tagClass, $expectedBaseClass)) {
            
            throw new ConfigurationException($tagClass." must be a child of ".$expectedBaseClass::class);
        }
        $this->tagClass = $tagClass;
    }

    /**
     * Gets class expected to handle the subtag
     * 
     * @return string
     */
    abstract protected function getChildTagBaseClass(): string;
}
