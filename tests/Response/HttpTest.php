<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\Headers;
use Lucinda\MVC\Response\Http;
use Lucinda\MVC\Response\HttpStatus;
use Lucinda\MVC\Response\View;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoBodyTransformer;
use Test\Lucinda\MVC\Support\DemoViewResolver;

class HttpTest
{
    public function setStatus()
    {
        $response = new Http();
        $response->setStatus(HttpStatus::NOT_FOUND);
        $reflection = new \ReflectionProperty($response, "status");
        $status = $reflection->getValue($response);

        return new Objects($status)->assertInstanceOf(HttpStatus::class);
    }

    public function setHeader()
    {
        $response = new Http();
        $response->setHeader("Authorization", "Bearer asdf");
        $reflection = new \ReflectionProperty($response, "headers");
        /** @var Headers $headers */
        $headers = $reflection->getValue($response);

        return new Arrays($headers->get())->assertContainsKey("Authorization");
    }

    public function run()
    {
        $response = new Http();
        ob_start();
        $response->run();
        $output = ob_get_clean();
        return new Strings($output)->assertEmpty();
    }

    public function setBody()
    {
        $response = new Http();
        $response->setBody("asd");
        ob_start();
        $response->run();
        $output = ob_get_clean();
        return new Strings($output)->assertEquals("asd");
    }

    public function resolve()
    {
        $response = new Http();
        $response->resolve(new View(["name" => "John"], "home"), new DemoViewResolver());
        ob_start();
        $response->run();
        $output = ob_get_clean();
        return new Strings($output)->assertContains("home:");
    }

    public function transformBody()
    {
        $response = new Http();
        $response->setBody("asd");
        $response->transformBody(new DemoBodyTransformer());
        ob_start();
        $response->run();
        $output = ob_get_clean();
        return new Strings($output)->assertEquals("ASD");
    }
}
