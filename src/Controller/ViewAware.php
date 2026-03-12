<?php

namespace Lucinda\MVC\Controller;

use Lucinda\MVC\Controller;
use Lucinda\MVC\Response\View;

interface ViewAware extends Controller
{
    function run(): View;
}
