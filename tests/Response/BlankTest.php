<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\Blank;
use Lucinda\MVC\Response\Headers;
use Lucinda\MVC\Response\HttpStatus;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class BlankTest
{
    public function setHeader()
    {
        $response = new Blank(HttpStatus::NO_CONTENT);
        $response->setHeader("X-Test", "value");
        $reflection = new \ReflectionProperty($response, "headers");
        /** @var Headers $headers */
        $headers = $reflection->getValue($response);
        return new Arrays($headers->get())->assertContainsKey("X-Test");
    }

    public function run()
    {
        $result = TestHelper::runPhp(
            'new \Lucinda\MVC\Response\Blank(\Lucinda\MVC\Response\HttpStatus::NO_CONTENT);'.
            '(new \Lucinda\MVC\Response\Blank(\Lucinda\MVC\Response\HttpStatus::NO_CONTENT))->run();'
        );

        return [
            (new Strings((string) $result["status"]))->assertEquals("0"),
            (new Strings($result["output"]))->assertEmpty()
        ];
    }
}
