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
    protected ?View $view = null;

    /**
     * Sets response body
     *
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }
    
    public function setView(View $view): void
    {
        $this->view = $view;
    }
    
    public function isAlreadyGenerated(): bool
    {
        return $this->body?true:false;
    }
    
    public function resolve(ViewResolver $resolver): void
    {
        if (!$this->view) {
            throw new ResponseException("View not set, therefore there is nothing to resolve");
        }
        if ($this->body) {
            throw new ResponseException("Response output stream has already been written to");
        }
        $this->body = $resolver->resolve($this->view);
        $this->view = null; // force conformity
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
