<?php
namespace Lucinda\MVC\Facets;

use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

class RouteInfo
{
    private string $id;
    private string $controller;
    private string $view;
    private string $format;
    
    public function __construct(Element $element)
    {
        $attributes = $element->getAttributes();
        if (empty($attributes["id"])) {
            throw new Exception("Attribute 'id' is mandatory for '".$element->getName()."' tag");
        }
        if (empty($attributes["controller"]) && empty($attributes["view"])) {
            throw new Exception("Attribute 'controller' or 'view' is mandatory for '".$element->getName()."' tag");
        }
        $this->id = $attributes["id"];
        $this->controller = $attributes["controller"]??"";
        $this->view = $attributes["view"]??"";
        $this->format = $attributes["format"]??"";
    }
    
    /**
     * Gets route unique identifier (eg: url)
     *
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }
    
    /**
     * Gets controller class name that handles exception handled.
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Gets file that holds what is displayed when error response is rendered.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Gets response format.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
