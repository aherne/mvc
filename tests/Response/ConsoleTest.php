<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\Console;
use Lucinda\MVC\Response\View;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoBodyTransformer;
use Test\Lucinda\MVC\Support\DemoViewResolver;

class ConsoleTest
{
    public function getExitCode()
    {
        return new Strings((string) (new Console(7))->getExitCode())->assertEquals("7");
    }

    public function setBody()
    {
        $stream = fopen("php://temp", "w+");
        $response = new Console(0, $stream);
        $response->setBody("asd");
        $response->run();
        rewind($stream);
        return new Strings(stream_get_contents($stream))->assertEquals("asd");
    }

    public function resolve()
    {
        $stream = fopen("php://temp", "w+");
        $response = new Console(0, $stream);
        $response->resolve(new View(["name" => "John"], "home"), new DemoViewResolver());
        $response->run();
        rewind($stream);
        return new Strings(stream_get_contents($stream))->assertContains("home:");
    }

    public function transformBody()
    {
        $stream = fopen("php://temp", "w+");
        $response = new Console(0, $stream);
        $response->setBody("asd");
        $response->transformBody(new DemoBodyTransformer());
        $response->run();
        rewind($stream);
        return new Strings(stream_get_contents($stream))->assertEquals("ASD");
    }

    public function run()
    {
        $stream = fopen("php://temp", "w+");
        $response = new Console(0, $stream);
        $response->run();
        rewind($stream);
        return new Strings(stream_get_contents($stream))->assertEmpty();
    }
}
