<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\Controller\ViewAware;
use Lucinda\MVC\Response\View;

final class DemoViewAwareController implements ViewAware
{
    public function run(): View
    {
        return new View(["controller" => "view-aware"], "home");
    }
}
