<?php

namespace Lucinda\MVC\XmlTags;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\Response\Resolver;
use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

/**
 * Detects view resolver information from <resolver> XML tag
 */
class ResolverInfo extends XmlElementInfo
{
    protected string $format;
    protected string $viewResolverClass;
    
    /**
     * Reads <resolver> XML tag
     * 
     * @param Element $element
     */
    protected function parse(Element $element): void
    {
        $attributes = $element->getAttributes();
        $this->setFormat($attributes);
        $this->setViewResolver($attributes);
    }
    
    /**
     * Sets content format
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setFormat(array $attributes): void
    {
        if (empty($attributes["format"])) {
            throw new Exception("Attribute 'format' is mandatory for 'resolver' tag");
        }
        $this->format = $attributes["format"];
    }
    
    /**
     * Gets content format
     *
     * @return  string
     * @example json
     */
    public function getFormat(): string
    {
        return $this->format;
    }
    
    /**
     * Sets content format
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setViewResolver(array $attributes): void
    {
        if (empty($attributes["class"])) {
            throw new Exception("Attribute 'class' is mandatory for 'resolver' tag");
        }
        if (!is_subclass_of($attributes["class"], Resolver::class)) {
            throw new ConfigurationException($attributes["class"]." must be child of ".Resolver::class);
        }
        $this->viewResolverClass = $attributes["class"];
    }
    
    /**
     * Gets view resolver class name
     *
     * @return  string
     * @example JsonResolver
     */
    public function getViewResolver(): string
    {
        return $this->viewResolverClass;
    }
}
