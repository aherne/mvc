<?php
namespace Lucinda\MVC\Facets;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

class ApplicationInfo
{
    protected string $defaultFormat;
    protected string $defaultRoute;
    protected string $version;
     
    public function __construct(Element $element)
    {
        $attributes = $element->getAttributes();
        $this->setDefaultFormat($attributes);
        $this->setDefaultRoute($attributes);
        $this->version = $attributes["version"]??"";
    }

    /**
     * Sets default response format
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setDefaultFormat(array $attributes): void
    {
        if (empty($attributes["default_format"])) {
            throw new Exception("Attribute 'default_format' is mandatory for 'application' tag");
        }
        $this->defaultFormat = $attributes["default_format"];
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
     * Sets default route id
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setDefaultRoute(array $attributes): void
    {
        if (empty($attributes["default_route"])) {
            throw new Exception("Attribute 'default_route' is mandatory for 'application' tag");
        }
        $this->defaultRoute = $attributes["default_route"];
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
     * Sets application version
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setVersion(array $attributes): void
    {
        $this->version = $attributes["version"]??"";
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

