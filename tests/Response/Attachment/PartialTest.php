<?php

namespace Test\Lucinda\MVC\Response\Attachment;

use Lucinda\UnitTest\Validator\Files;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class PartialTest
{
    public function run()
    {
        $file = TestHelper::createTempFile("abcdef");
        $result = TestHelper::runPhp(
            '$response = new \Lucinda\MVC\Response\Attachment\Partial('.
            var_export($file, true).', "bytes=1-3", true);'.
            '$response->run();'
        );

        return [
            (new Strings((string) $result["status"]))->assertEquals("0"),
            (new Strings($result["output"]))->assertEquals("bcd"),
            (new Files($file))->assertNotExists()
        ];
    }
}
