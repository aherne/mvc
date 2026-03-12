<?php
namespace Lucinda\MVC\Response\Transformer;

use Lucinda\MVC\Response\HttpStatus;

interface Status extends Transformer
{
    function getHttpStatus(): HttpStatus;
}
