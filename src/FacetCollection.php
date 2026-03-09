<?php

namespace Lucinda\MVC;

final class FacetCollection
{
    private array $facets = [];

    public function add(string $key, Facet $facet): void {
        $this->facets[$key] = $facet;
    }

    public function getAll(): array {
        if (empty($this->facets)) {
            throw new FacetException("Facets collection cannot be empty!");
        }
        return $this->facets;
    }
}
