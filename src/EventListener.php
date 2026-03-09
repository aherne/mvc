<?php

namespace Lucinda\MVC;

interface EventListener
{
    function run(): Facet|array|null;
}