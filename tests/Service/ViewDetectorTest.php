<?php

namespace Test\Lucinda\MVC\Service;

use Lucinda\MVC\Response\View;
use Lucinda\MVC\Service\ViewDetector;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoApplication;
use Test\Lucinda\MVC\Support\DemoRequestValidator;

class ViewDetectorTest
{
    public function getView()
    {
        $application = new DemoApplication(dirname(__DIR__)."/fixtures/root.xml");
        $filledView = new View(["name" => "John"], "home");
        $detector = new ViewDetector($application, new DemoRequestValidator("index", "json"), $filledView);
        $view = $detector->getView();

        return [
            (new Objects($view))->assertInstanceOf(View::class),
            (new Strings($view->getFile()))->assertEquals("tests/fixtures/views/home.phtml"),
            (new Arrays($view->getData()))->assertContainsKey("name")
        ];
    }
}
