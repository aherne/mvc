<?php

namespace Lucinda\MVC\Response;

/**
 * Implements a response expected to be displayed in a console / terminal
 */
final class Console extends Basic
{
    private int $exitCode;
    /**
     * @var resource
     */
    private $stream = STDOUT;

    /**
     * Prepares console response
     */
    public function __construct(int $exitCode = 0, $stream = STDOUT)
    {
        $this->exitCode = $exitCode;
        $this->stream = $stream;
    }
    
    /**
     * Gets exit code set
     */
    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    /**
     * Sends response body back to caller
     * 
     * @param string $body
     */
    protected function emit(string $body): void
    {
        fwrite($this->stream, $body);
    }
}