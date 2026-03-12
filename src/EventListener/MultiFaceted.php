<?php

namespace Lucinda\MVC\EventListener;

use Lucinda\MVC\EventListener;
use Lucinda\MVC\FacetCollection;

interface MultiFaceted extends EventListener
{
    function run(): FacetCollection;
}