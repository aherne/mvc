<?php

namespace Lucinda\MVC;

use Lucinda\MVC\Response\Blank as ResponseEmpty;
use Lucinda\MVC\Response\ByStatus as ResponseByStatus;
use Lucinda\MVC\Response\Redirect as ResponseRedirect;
use Lucinda\MVC\Response\Basic as ResponseByBody;
use Lucinda\MVC\Response\Attachment\File as ResponseByFileAttachment;
use Lucinda\MVC\Response\Attachment\Partial as ResponseByFilePartialAttachment;
use Lucinda\MVC\Response\Attachment\Streamed as ResponseByFileStreamedAttachment;


final class TerminationException extends \Exception implements Runnable
{
    private Runnable $runnable;

    public function __construct(ResponseEmpty|ResponseByStatus|ResponseRedirect|ResponseByBody|ResponseByFileAttachment|ResponseByFilePartialAttachment|ResponseByFileStreamedAttachment $response) {
        $this->runnable = $response;
    }

    public function run(): void
    {
        $this->runnable->run();
    }
}