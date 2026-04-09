<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\View;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class ViewTest
{
    public function setFile()
    {
        $view = new View([]);
        $view->setFile("admin");
        return new Strings($view->getFile())->assertEquals("admin");
    }

    public function getFile()
    {
        return new Strings((new View([], "index"))->getFile())->assertEquals("index");
    }

    public function setData()
    {
        $view = new View([]);
        $view->setData(["test3" => "me3"]);
        return new Arrays($view->getData())->assertEquals(["test3" => "me3"]);
    }

    public function getData()
    {
        return new Arrays((new View(["test1" => "me1", "test2" => "me2"], "index"))->getData())->assertEquals(
            ["test1" => "me1", "test2" => "me2"]
        );
    }
}
