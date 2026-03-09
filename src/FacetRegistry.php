<?php

namespace Lucinda\MVC;

final class FacetRegistry
{
    /** @var array<class-string, object> */
    private array $facets = [];
    
    public function put(Facet $facet): void
    {
        $this->facets[$facet::class] = $facet;
    }
    
    public function putAs(string $type, Facet $facet): void
    {
        $this->facets[$type] = $facet; // for interface aliases
    }
    
    public function get(string $type): Facet
    {
        if (!isset($this->facets[$type])) {
            throw new FacetException("Missing facet: ".$type);
        }
        return $this->facets[$type];
    }
    
    public function has(string $type): bool
    {
        return isset($this->facets[$type]);
    }
}