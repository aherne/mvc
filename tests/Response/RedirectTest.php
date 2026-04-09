<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\Redirect;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class RedirectTest
{
    public function setPreventCaching()
    {
        $response = new Redirect("https://www.google.com");
        $response->setPreventCaching(true);
        $reflection = new \ReflectionProperty($response, "preventCaching");
        return new Booleans($reflection->getValue($response))->assertTrue();
    }

    public function run()
    {
        $result = TestHelper::runPhp(
            '(new \Lucinda\MVC\Response\Redirect("https://www.google.com"))->run();'
        );

        return [
            (new Strings((string) $result["status"]))->assertEquals("0"),
            (new Strings($result["output"]))->assertEmpty()
        ];
    }
}
