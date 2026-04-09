<?php

namespace Test\Lucinda\MVC;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\Response\Console;
use Lucinda\MVC\TerminationException;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\DemoResponse;

class TerminationExceptionTest
{
    public function getResponse()
    {
        $results = [];
        $response = new DemoResponse();
        $exception = new TerminationException($response);

        $results[] = (new Booleans($exception->getResponse() === $response))->assertTrue();

        try {
            new TerminationException(new Console());
            $results[] = (new Strings(""))->assertNotEmpty("basic response should fail");
        } catch (ConfigurationException $e) {
            $results[] = (new Strings($e->getMessage()))->assertContains("should not be terminated abruptly");
        }

        return $results;
    }
}
