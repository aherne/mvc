<?php
namespace Lucinda\MVC\Facets;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

class ApplicationInfo
{
    protected array $mandatoryFields = ["default_format", "default_route"];
    private string $defaultFormat;
    private string $defaultRoute;
    private string $version;
 
    
    public function __construct(Element $element)
    {
        $attributes = $element->getAttributes();
        foreach ($this->mandatoryFields as $field) {
            if (empty($attributes[$field])) {
                throw new Exception("Attribute '".$field."' is mandatory for '".$element->getName()."' tag");
            }
        }
        
        $this->defaultFormat = $attributes["default_format"];
        $this->defaultRoute = $attributes["default_route"];        
        $this->version = $attributes["version"]??"";
    }
    
    /**
     * Gets default response display format
     *
     * @return string
     */
    public function getDefaultFormat(): string
    {
        return $this->defaultFormat;
    }
    
    /**
     * Gets default route id
     *
     * @return string
     */
    public function getDefaultRoute(): string
    {
        return $this->defaultRoute;
    }
    
    /**
     * Gets application version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }    
}

