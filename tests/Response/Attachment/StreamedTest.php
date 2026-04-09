<?php

namespace Test\Lucinda\MVC\Response\Attachment;

use Lucinda\UnitTest\Validator\Files;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class StreamedTest
{
    public function run()
    {
        $file = TestHelper::createTempFile("abcdef");
        $result = TestHelper::runPhp(
            '$response = new \Lucinda\MVC\Response\Attachment\Streamed('.
            var_export($file, true).', true, 2);'.
            '$response->run();'
        );

        return [
            (new Strings((string) $result["status"]))->assertEquals("0"),
            (new Strings($result["output"]))->assertEquals("abcdef"),
            (new Files($file))->assertNotExists()
        ];
    }
}
