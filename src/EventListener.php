<?php

namespace Lucinda\MVC;

interface EventListener
{
    /**
     * @return Facet|FacetCollection|null
     */
    function run(): Facet|FacetCollection|null;
}