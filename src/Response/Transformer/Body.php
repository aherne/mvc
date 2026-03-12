<?php
namespace Lucinda\MVC\Response\Transformer;

interface Body extends Transformer
{
    function transform(string $source): string;
}
