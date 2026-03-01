<?php

namespace Lucinda\MVC;

use Lucinda\MVC\Response\View;
use Lucinda\MVC\Response\ViewResolver;
use Lucinda\MVC\Response\Exception as ResponseException;

/**
 * Compiles information about response
 */
class Response
{
    protected ?string $body = null;

    /**
     * Sets response body
     *
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }
    
    public function getBody(): ?string
    {
        return $this->body;
    }
    
    public function isAlreadyGenerated(): bool
    {
        return $this->body?true:false;
    }
    
    public function resolve(View $view, ViewResolver $resolver): void
    {
        if ($this->body) {
            throw new ResponseException("Response output stream has already been written to");
        }
        $this->body = $resolver->resolve($view);
    }

    /**
     * Commits response to client.
     */
    public function commit(): void
    {
        // displays body
        echo $this->body;
    }
}
