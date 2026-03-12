<?php

namespace Lucinda\MVC\EventListener;

use Lucinda\MVC\EventListener;
use Lucinda\MVC\Facet;

interface Faceted extends EventListener
{
    function run(): Facet;
}