<?php
namespace Lucinda\MVC\Response\Transformer;

use Lucinda\MVC\Response\Headers as HttpHeaders;
use Lucinda\MVC\Response\HttpStatus;

interface Headers extends Transformer
{
    function getExtraHeaders(): HttpHeaders;
}
