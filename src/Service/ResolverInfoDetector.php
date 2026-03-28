<?php

namespace Lucinda\MVC\Service;

use Lucinda\MVC\ConfigurationException;
use Lucinda\MVC\Application;
use Lucinda\MVC\XmlTags\ResolverInfo;
use Lucinda\MVC\RequestValidator;

/**
 * Detects view resolver info by binding Application and RequestValidator objects received
 */
final class ResolverInfoDetector
{
    private ResolverInfo $resolver;
    
    /**
     * Bootstraps the binding process
     * 
     * @param Application $application
     * @param RequestValidator $validatedRequest
     */
    public function __construct(
        Application $application,
        RequestValidator $validatedRequest
    )
    {
        $this->setResolver($application, $validatedRequest);
    }

    /**
     * Detects the view resolver to use
     * 
     * @param Application $application
     * @param RequestValidator $validatedRequest
     * @throws ConfigurationException If resolver not set
     */
    private function setResolver(
        Application $application,
        RequestValidator $validatedRequest
    ): void
    {
        $resolver = $application->getResolvers($validatedRequest->getFormat());
        if ($resolver === null) {
            throw new ConfigurationException("Resolver not set for: ".$validatedRequest->getFormat());
        }
        $this->resolver = $resolver;
    }

    /**
     * Gets view resolver information detected
     * 
     * @return ResolverInfo
     */
    public function getResolver(): ResolverInfo
    {
        return $this->resolver;
    }
}
