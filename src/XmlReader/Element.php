<?php
namespace Lucinda\MVC\XmlReader;

/**
 * Hides complexity of SimpleXMLElement from outside world by a simple wrapper
 */
final class Element
{
    private string $name;
    /**
     * @var array<string,string>
     */
    private array $attributes=[];
    /**
     * @var array<string,Element[]>
     */
    private array $children=[];

    /**
     * Bootstraps the process.
     * 
     * @param \SimpleXMLElement $node
     */
    public function __construct(\SimpleXMLElement $node)
    {
        $this->setName($node);
        $this->setAttributes($node);
        $this->setChildren($node);
    }
    
    /**
     * Detects tag name
     * 
     * @param \SimpleXMLElement $node
     */
    private function setName(\SimpleXMLElement $node): void
    {
        $this->name = $node->getName();
    }
    
    /**
     * Gets tag name detected
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Detects tag attributes
     * 
     * @param \SimpleXMLElement $node
     */
    private function setAttributes(\SimpleXMLElement $node): void
    {
        foreach ($node->attributes() as $k => $v) {
            $this->attributes[(string) $k] = (string) $v;
        }
    }
    
    /**
     * Gets tag attributes detected
     * 
     * @return array<string,string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Recursively detects tag children
     * 
     * @param \SimpleXMLElement $node
     */
    private function setChildren(\SimpleXMLElement $node): void
    {
        foreach ($node->children() as $child) {
            $this->children[$child->getName()][] = new Element($child);
        }
    }
    
    /**
     * Gets tag children detected as lists by child tag name
     * 
     * 
     * @return array<string,Element[]>
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}