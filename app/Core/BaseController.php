<?php

namespace app\Core;

abstract class BaseController
{
    protected array $styles = [];
    protected array $scripts = [];

    /**
     * Set variable in View
     * @param View $view
     * @param string $variableName
     * @param mixed $value
     * @return void
     */
    protected function setViewVariable(View $view, string $variableName, mixed $value): void
    {
        try {
            $view->setVariable($variableName, $value);
        }catch (\Exception $exception){
            return;
        }
    }
}