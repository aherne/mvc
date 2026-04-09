<?php

namespace Test\Lucinda\MVC\Response;

use Lucinda\MVC\Response\Headers;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Booleans;

class HeadersTest
{
    public function add()
    {
        $headers = new Headers();
        $headers->add("Authorization", "Bearer asdf");
        return new Arrays($headers->get())->assertContainsKey("Authorization");
    }

    public function get()
    {
        $headers = new Headers();
        $headers->add("X-Test", ["one", "two"]);
        return new Arrays($headers->get())->assertEquals(["X-Test" => ["one", "two"]]);
    }

    public function send()
    {
        $headers = new Headers();
        $headers->add("X-Test", "value");
        $headers->send();
        return new Booleans(true)->assertTrue();
    }
}
