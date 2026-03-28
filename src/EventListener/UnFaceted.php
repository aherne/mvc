<?php

namespace Lucinda\MVC\EventListener;

use Lucinda\MVC\EventListener;
use Lucinda\MVC\Runnable;

/**
 * Requires that the EventListener will return nothing when ran
 */
interface UnFaceted extends EventListener, Runnable
{
}