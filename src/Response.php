<?php

namespace Lucinda\MVC;

use Lucinda\MVC\Response\View;
use Lucinda\MVC\Response\ViewResolver;
use Lucinda\MVC\Response\Exception as ResponseException;
use Lucinda\MVC\Response\Transformer as ResponseTransformer;

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
    
    public function isAlreadyComposed(): bool
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
    
    public function transform(ResponseTransformer $transformer): void
    {
        if (!$this->body) {
            throw new ResponseException("Response output stream has not been written to");
        }
        $this->body = $transformer->transform($this->body);
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
