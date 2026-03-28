<?php
namespace Lucinda\MVC\XmlTags;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

/**
 * Detects information from a XML tag origin
 */
abstract class XmlElementInfo
{
    /**
     * Parses information obtained from the XML tag
     * 
     * @param Element $element
     */
    public function __construct(Element $element)
    {
        $this->parse($element);
    }

    /**
     * Parses information obtained from the XML tag
     * 
     * @param Element $element
     * @throws Exception If XML is misconfigured.
     */
    abstract protected function parse(Element $element);
}
