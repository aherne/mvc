<?php
namespace Lucinda\MVC\Response;

interface ViewResolver extends Resolver
{
    function resolve(View $view): string;
}
