<?php

namespace Lucinda\MVC;

use Lucinda\MVC\Response\View;

interface Controller
{
    function run(): Response|View|null;
}
