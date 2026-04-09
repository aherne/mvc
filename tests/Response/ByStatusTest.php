<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\ByStatus;
use Lucinda\MVC\Response\Headers;
use Lucinda\MVC\Response\HttpStatus;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class ByStatusTest
{
    public function setHeader()
    {
        $response = new ByStatus(HttpStatus::BAD_REQUEST);
        $response->setHeader("X-Test", "value");
        $reflection = new \ReflectionProperty($response, "headers");
        /** @var Headers $headers */
        $headers = $reflection->getValue($response);
        return new Arrays($headers->get())->assertContainsKey("X-Test");
    }

    public function setBody()
    {
        $response = new ByStatus(HttpStatus::BAD_REQUEST);
        $response->setBody("hello");
        $reflection = new \ReflectionProperty($response, "body");
        return new Strings((string) $reflection->getValue($response))->assertEquals("hello");
    }

    public function run()
    {
        $result = TestHelper::runPhp(
            '$response = new \Lucinda\MVC\Response\ByStatus(\Lucinda\MVC\Response\HttpStatus::BAD_REQUEST);'.
            '$response->setBody("hello");'.
            '$response->run();'
        );

        return [
            (new Strings((string) $result["status"]))->assertEquals("0"),
            (new Strings($result["output"]))->assertEquals("hello")
        ];
    }
}
