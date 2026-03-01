<?php
namespace Lucinda\MVC\Response;

interface Transformer
{
    function transform(string $source): string;
}

