<?php

namespace Test\Lucinda\MVC\Support;

use Lucinda\MVC\Response\View;
use Lucinda\MVC\Response\ViewResolver;

final class DemoViewResolver implements ViewResolver
{
    public function resolve(View $view): string
    {
        return $view->getFile().":".json_encode($view->getData());
    }
}
