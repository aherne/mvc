<?php

namespace Test\Lucinda\MVC\Support;

final class NeedsAliasedFacet
{
    public function __construct(public DemoFacetAlias $facet)
    {
    }
}
