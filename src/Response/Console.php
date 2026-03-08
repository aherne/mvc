<?php

namespace Lucinda\MVC\Response;

abstract class Console extends Basic
{
    private ?int $exitCode = null;
    private ?string $errorBody = null;

    public function setExitCode(int $exitCode): void
    {
        if ($exitCode < 0 || $exitCode > 255) {
            throw new Exception("Invalid exit code: ".$exitCode);
        }
        $this->exitCode = $exitCode;
    }

    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    /**
     * Sets response body
     *
     * @param string $body
     */
    public function setErrorBody(string $errorBody): void
    {
        $this->errorBody = $errorBody;
    }

    protected function emit(string $body): void
    {
        if ($body) {
            fwrite(STDOUT, $body);
        }
        if ($this->errorBody) {
            fwrite(STDERR, $this->errorBody);
        }
    }
}