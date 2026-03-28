<?php

namespace Lucinda\MVC;

/**
 * Defines blueprints of a request validator
 */
interface RequestValidator
{
    /**
     * Gets final route after validation process
     */
    function getRoute(): string;

    /**
     * Gets final response format after validation process
     */
    function getFormat(): string;
}