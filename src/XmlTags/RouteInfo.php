<?php
namespace Lucinda\MVC\XmlTags;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\Controller\ViewAware;
use Lucinda\MVC\Controller\ViewUnaware;
use Lucinda\MVC\XmlReader\Element;
use Lucinda\MVC\XmlReader\Exception;

/**
 * Detects route information from <route> XML tag
 */
class RouteInfo extends XmlElementInfo
{
    protected string $id;
    protected string $controller;
    protected string $view;
    protected string $format;
    
    /**
     * Reads <route> XML tag
     * 
     * @param Element $element
     */
    protected function parse(Element $element): void
    {
        $attributes = $element->getAttributes();
        $this->setID($attributes);
        $this->setFormat($attributes);
        $this->setController($attributes);
        $this->setView($attributes);
        if (!$this->controller && !$this->view) {
            throw new Exception("Attribute 'controller' or 'view' is mandatory for 'route' tag");
        }
    }

    /**
     * Sets route id
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured.
     */
    protected function setID(array $attributes): void
    {
        if (empty($attributes["id"])) {
            throw new Exception("Attribute 'id' is mandatory for 'route' tag");
        }
        $this->id = $attributes["id"];
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
     * Sets controller
     * 
     * @param array<string,string> $attributes
     * @throws Exception If XML is misconfigured (wrong controller is defined).
     */
    protected function setController(array $attributes): void
    {
        if (empty($attributes["controller"])) {
            return;
        }
        if (!(
            is_subclass_of($attributes["controller"], ViewAware::class) || 
            is_subclass_of($attributes["controller"], ViewUnaware::class)
            )
        ) {
            throw new ConfigurationException(
                $attributes["controller"]." must be a child of ".ViewAware::class." or ".ViewUnaware::class
            );
        }
        $this->controller = $attributes["controller"]??"";
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
     * Sets view
     * 
     * @param array<string,string> $attributes
     */
    protected function setView(array $attributes): void
    {
        $this->view = $attributes["view"]??"";
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
     * Sets response format
     * 
     * @param array<string,string> $attributes
     */
    protected function setFormat(array $attributes): void
    {
        $this->format = $attributes["format"]??"";
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
