<?php

namespace Lucinda\MVC\Controller;

use Lucinda\MVC\Controller;
use Lucinda\MVC\Runnable;

/**
 * Requires that the outcome of the controller will return nothing when ran
 */
interface ViewUnaware extends Controller, Runnable
{
}
