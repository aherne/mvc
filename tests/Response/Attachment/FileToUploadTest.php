<?php

namespace Test\Lucinda\MVC\Response\Attachment;

use Lucinda\MVC\Response\Attachment\FileToUpload;
use Lucinda\UnitTest\Validator\Files;
use Lucinda\UnitTest\Validator\Integers;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\MVC\Support\TestHelper;

class FileToUploadTest
{
    public function getMimeType()
    {
        $file = TestHelper::createTempFile("hello world");
        $object = new FileToUpload($file);
        $result = new Strings($object->getMimeType());
        unlink($file);
        return $result->assertNotEmpty();
    }

    public function getSimpleName()
    {
        $file = TestHelper::createTempFile("hello world");
        $object = new FileToUpload($file);
        $result = new Strings($object->getSimpleName());
        unlink($file);
        return $result->assertEquals(basename($file));
    }

    public function getSize()
    {
        $file = TestHelper::createTempFile("hello world");
        $object = new FileToUpload($file);
        $result = new Integers($object->getSize());
        unlink($file);
        return $result->assertEquals(11);
    }

    public function cleanup()
    {
        $file = TestHelper::createTempFile("hello world");
        $object = new FileToUpload($file);
        $object->cleanup();
        return new Files($file)->assertNotExists();
    }
}
