<?php
namespace Lucinda\MVC\Response;

interface ViewResolver
{
    function resolve(View $view): string;
}
