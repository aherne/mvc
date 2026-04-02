<?php

namespace Lucinda\MVC\Service;

use Lucinda\MVC\Response\View;
use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\Application;
use Lucinda\MVC\RequestValidator;

/**
 * Compiles final view by binding Application, RequestValidator and optional controller-derived View objects
 */
final class ViewDetector
{
    private ?View $view = null;
    
    /**
     * Bootstraps the compilation process
     * 
     * @param Application $application
     * @param RequestValidator $validatedRequest
     * @param ?View $filledView
     */
    public function __construct(
        Application $application,
        RequestValidator $validatedRequest,
        ?View $filledView
    )
    {
        $template = $this->getTemplate($application, $validatedRequest, $filledView);
        if ($template === null && $filledView === null) {
            return;
        }
        $this->view = new View(
            $filledView?->getData() ?? [],
            $template
        );
    }

    /**
     * Detects location of view template file, if any
     * 
     * @param Application $application
     * @param RequestValidator $validatedRequest
     * @param ?View $filledView
     * @return ?string
     * @throws ConfigurationException If application is wrongly configured
     */
    private function getTemplate(
        Application $application,
        RequestValidator $validatedRequest,
        ?View $filledView
    ): ?string
    {
        $info = $application->getApplicationInfo();
        $viewsFolder = $info->getViewsFolder();
        $viewsExtension = $info->getViewsExtension();
        $template = $filledView?->getFile()?:$application->getRoutes($validatedRequest->getRoute())->getView();

        $fullViewPath = null;
        if (!empty($template)) {
            if (empty($viewsFolder) || empty($viewsExtension)) {
                throw new ConfigurationException(
                    "If views are used, setting 'views_folder' attribute in 'application' tag is required"
                );
            }
            $fullViewPath = $viewsFolder . DIRECTORY_SEPARATOR . $template . "." . $viewsExtension;
            if (!file_exists($fullViewPath)) {
                throw new ConfigurationException(
                    "View file doesn't exist: ". $fullViewPath
                );
            }
        }
        return $fullViewPath;
    }

    /**
     * Gets view detected
     * 
     * @return View
     */
    public function getView(): ?View
    {
        return $this->view;
    }
}
