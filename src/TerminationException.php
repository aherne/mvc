<?php

namespace Lucinda\MVC;

final class TerminationException extends \RuntimeException
{
    private Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}