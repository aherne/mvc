<?php

namespace Lucinda\MVC\Response;

use Lucinda\MVC\Response;
use Lucinda\MVC\Runnable;

/**
 * Compiles information about a basic string based response
 */
abstract class Basic implements Runnable, Response
{
    private ?string $body = null;

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
        return $this->body!==null;
    }
    
    public function resolve(View $view, ViewResolver $resolver): void
    {
        if ($this->body!==null) {
            throw new Exception("Response output stream has already been written to");
        }
        $this->body = $resolver->resolve($view);
    }
    
    public function transform(Transformer $transformer): void
    {
        if ($this->body===null) {
            throw new Exception("Response output stream has not been written to");
        }
        $this->body = $transformer->transform($this->body);
    }

    /**
     * Commits response to client.
     */
    public function run(): void
    {
        $this->emit($this->body??"");
    }

    /**
     * Sends response body back to caller
     */
    abstract protected function emit(string $body): void;
}
