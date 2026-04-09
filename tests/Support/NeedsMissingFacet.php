<?php

namespace Test\Lucinda\MVC\Support;

final class NeedsMissingFacet
{
    public function __construct(public DemoFacet $facet)
    {
    }
}
