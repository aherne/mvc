<?php

namespace Test\Lucinda\MVC\Support;

final class NeedsDemoFacet
{
    public function __construct(public DemoFacet $facet)
    {
    }
}
