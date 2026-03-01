<?php

namespace Lucinda\MVC\Facets;


use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

/**
 * Encapsulates file format information:
 * - format: file format / name
 * - content type: content type that corresponds to above file format
 * - character encoding: charset associated to content type
 * - view resolver: (optional) view resolver class name. If not set, framework will resolve into an empty view with headers only.
 */
class ResolverInfo
{
    protected array $mandatoryFields = ["format", "class"];
    
    private string $format;
    private string $viewResolverClass;
    
    
    public function __construct(Element $element)
    {
        $attributes = $element->getAttributes();
        foreach ($this->mandatoryFields as $field) {
            if (empty($attributes[$field])) {
                throw new Exception("Attribute '".$field."' is mandatory for '".$element->getName()."' tag");
            }
        }
        
        $this->format = $attributes["format"];
        $this->viewResolverClass = $attributes["class"];
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
