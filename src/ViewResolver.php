<?php

namespace Lucinda\MVC;

/**
 * Resolves View into a Response based on Application settings
 */
abstract class ViewResolver implements Runnable
{
    protected Application $application;
    protected Response $response;

    /**
     * Saves objects to be available in implemented getContent() methods.
     *
     * @param Application $application
     * @param Response    $response
     */
    public function __construct(Application $application, Response $response)
    {
        $this->application = $application;
        $this->response = $response;
    }
}
