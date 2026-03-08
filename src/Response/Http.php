<?php

namespace Lucinda\MVC\Response;

use Lucinda\MVC\Response\Attachment\Partial;

/**
 * Compiles information about an a basic http response
 */
abstract class Http extends Basic
{
    protected ?HttpStatus $status = null;
    protected ?Headers $headers = null;

    /**
     * Sets HTTP response status by its numeric code.
     *
     * @param HttpStatus $status
     */
    public function setStatus(HttpStatus $status): void
    {
        if (in_array($status->value, Blank::SUPPORTED_STATUSES)) {
            throw new Exception("For ".$status->value." http status use this class: ".Blank::class);
        } else if (in_array($status->value, Partial::SUPPORTED_STATUSES)) {
            throw new Exception("For ".$status->value." http status use this class: ".Partial::class);
        } else if (in_array($status->value, Redirect::SUPPORTED_STATUSES)) {
            throw new Exception("For ".$status->value." http status use this class: ".Redirect::class);
        }
        $this->status = $status;
    }

    /**
     * Sets response header
     *
     * @param string $key
     * @param string|string[] $value
     * @return void
     */
    public function setHeader(string $key, string|array $value): void
    {
        if ($this->headers === null) {
            throw new Exception("Headers not initialized");
        }
        $this->headers->add($key, $value);
    }

    
    /**
     * Commits response to client.
     */
    public function run(): void
    {
        // sends headers
        if (headers_sent()) {
            throw new Exception("Headers were sent already!");
        }

        if ($this->status!==null) {
            http_response_code($this->status->value);
        }

        if ($this->headers!==null) {
            $this->headers->send();
        }

        // displays body
        parent::run();
    }

    protected function emit(string $body): void
    {
        echo $body;
    }
}